<?php namespace BibleBowl\Http\Controllers\Seasons;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupJoinRequest;
use BibleBowl\Http\Requests\SeasonRegistrationRequest;
use BibleBowl\Player;
use BibleBowl\Program;
use BibleBowl\Seasons\SeasonRegistrar;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Input;
use Session;

class PlayerRegistrationController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function program($action)
    {
        return view('seasons.registration.program')
            ->withAction($action)
            ->withPrograms(Program::all());
    }

	/**
	 * @return \Illuminate\View\View
	 */
	public function findGroupToRegister($programSlug)
	{
        // follow registration links
        $familiarGroup = $this->groupToRedirectTo($programSlug);
        if (!is_null($familiarGroup)) {
            return redirect('/register/'.$programSlug.'/group/'.$familiarGroup->id);
        }

        $program = Program::slug($programSlug)->firstOrFail();
		return view('seasons.registration.register_find_group')
            ->with('program', $program)
            ->with('nearbyGroups', $this->findNearbyGroups($program));
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function findGroupToJoin($programSlug)
	{
        // follow registration links
        $familiarGroup = $this->groupToRedirectTo($programSlug);
        if (!is_null($familiarGroup)) {
            return redirect('/join/'.$programSlug.'/group/'.$familiarGroup->id);
        }

        $program = Program::slug($programSlug)->firstOrFail();

		return view('seasons.registration.join_find_group')
            ->with('program', $program)
            ->with('nearbyGroups', $this->findNearbyGroups($program))
            ->with('familiarGroup', Session::getGroupToRegisterWith());
	}

    /**
     * @return \Illuminate\View\View
     */
    public function getRegister($programSlug, $group = null)
    {
        if (is_null($group) === false) {
			$group = Group::findOrFail($group);
		}

        return view('seasons.registration.register_form')
            ->with('program', Program::where('slug', $programSlug)->firstOrFail())
            ->withGroup($group);
    }

	/**
	 * @return mixed
	 */
	public function postRegister(SeasonRegistrationRequest $request, SeasonRegistrar $registrar, $programSlug, $group = null)
	{
		$this->validate($request, $request->rules());

		// map the POSTed data to the season data required
        $program = Program::where('slug', $programSlug)->firstOrFail();
		$seasonData = [];
		foreach ($request->get('player') as $playerId => $playerData) {
			if (isset($playerData['register']) && $playerData['register'] == 1) {
				$seasonData[$playerId] = [
					'grade' 		=> $playerData['grade'],
					'shirt_size' 	=> $playerData['shirtSize']
				];
			}
		}

        $registrar->register(Session::season(), $group, Auth::user(), $program, $seasonData);

		return redirect('/dashboard')->withFlashSuccess('Your player(s) have been registered!');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getJoin($programSlug, $group)
	{
		return view('seasons.registration.join_form')
			->withGroup(Group::findOrFail($group))
			->withPlayers(Auth::user()->players()->registeredWithNBBOnly(Session::season())->get());
	}

	public function postJoin(GroupJoinRequest $request, GroupRegistrar $registrar, $programSlug, $group)
	{
		$this->validate($request, $request->rules());

		$group = Group::findOrFail($group);

		$registrar->register(Session::season(), $group, Auth::user(), array_keys($request->get('player')));

		return redirect('/dashboard')->withFlashSuccess('Your player(s) have joined a group!');
	}

	/**
	 * Remember the group the user is trying to register for
	 */
	public function rememberGroup($guid)
	{
        Session::setGroupToRegisterWith($guid);

		return redirect('/');
	}

	public static function viewBindings()
	{
		\View::creator('seasons.registration.search_group', function (View $view) {
            $searchResults = null;
			if (Input::has('q')) {
				$searchResults = Group::where('program_id', $view->getData()['program']->id)
                    ->active()
                    ->where('name', 'LIKE', '%'.Input::get('q').'%')
                    ->get();
			}
			$view->with('searchResults', $searchResults);
		});

        \View::creator('seasons.registration.register_form', function (View $view) {
            $season = Session::season();
            $view->with('players', Auth::user()
                ->players()
                ->notRegisteredWithNBB($season, Auth::user())
                ->get()
            );
        });
	}

    /**
     * Find groups nearby based on the group type
     */
    private function findNearbyGroups(Program $program) {
        return Group::where('program_id', $program->id)
            ->near(Auth::user()->addresses->first())
            ->with('meetingAddress')
            ->limit(6)
            ->get();
    }

    /**
     * Get the group this user should be redirected to
     *
     * @param $programSlug
     *
     * @return \BibleBowl\Users\Auth\Group|null
     */
    private function groupToRedirectTo($programSlug)
    {
        // if followed a registration link and the program matches, skip this step
        $familiarGroup = Session::getGroupToRegisterWith();
        if (is_null($familiarGroup) === false && $programSlug == $familiarGroup->program->slug && Input::has('noRedirect') === false) {
            return $familiarGroup;
        }

        return null;
    }

}
