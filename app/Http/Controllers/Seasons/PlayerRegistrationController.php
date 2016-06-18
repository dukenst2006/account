<?php namespace BibleBowl\Http\Controllers\Seasons;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\PlayerRegistrationRequest;
use BibleBowl\Http\Requests\Request;
use BibleBowl\Program;
use BibleBowl\Season;
use BibleBowl\Seasons\GroupRegistration;
use DB;
use Illuminate\View\View;
use Input;
use Session;

class PlayerRegistrationController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getPlayers()
    {
        $season = Session::season();
        return view('seasons.registration.players')
            ->withSeason($season)
            ->withPlayers(Auth::user()
                ->players()
                ->notRegistered($season, Auth::user())
                ->get());
    }

    /**
     * @return mixed
     */
    public function postPlayers(
        PlayerRegistrationRequest $request,
        GroupRegistration $registration
    ) {

        // map the POSTed data to the season data required
        foreach ($request->get('player') as $playerId => $playerData) {
            if (isset($playerData['register']) && $playerData['register'] == 1) {
                $registration->addPlayer($playerId, $playerData['grade'], $playerData['shirtSize']);
            }
        }

        // if they followed a link to get to the registration
        // auto-associate them with this group
        $familiarGroup = Session::getGroupToRegisterWith();
        if ($familiarGroup !== null) {
            $registration->addGroup($familiarGroup);
        }

        // save in session so we can show this info on
        // the summary page
        Session::setSeasonalGroupRegistration($registration);

        // sometimes parents can override the program selection
        if ($registration->requiresProgramSelection()) {
            return redirect('/register/program');
        }

        return redirect('/register/summary');
    }
    /**
     * Prompt the parent/guardian to override certain programs
     *
     * @return \Illuminate\View\View
     */
    public function getChooseProgram()
    {
        /** @var GroupRegistration $registration */
        $registration = Session::seasonalGroupRegistration();

        return view('seasons.registration.partials.choose-program')
            ->withPrograms(Program::all())
            ->withRegistration($registration)
            ->withPlayers($registration->playersWithOptionalProgramSelection());
    }

    /**
     * Add program override for players to the registration
     *
     * @return mixed
     */
    public function postChooseProgram(Request $request)
    {
        /** @var GroupRegistration $registration */
        $registration = Session::seasonalGroupRegistration();

        // map the POSTed data to the season data required
        foreach ($request->get('player') as $playerId => $programId) {
            $registration->overrideProgram($playerId, $programId);
        }

        Session::setSeasonalGroupRegistration($registration);

        return redirect('/register/summary');
    }

    /**
     * Show the parent/guardian a summary of their registration
     *
     * @return \Illuminate\View\View
     */
    public function summary()
    {
        return view('seasons.registration.summary')
            ->withRegistration(Session::seasonalGroupRegistration());
    }

    /**
     * @return \Illuminate\View\View
     */
    public function findGroupToRegister($programSlug)
    {
        $program = Program::slug($programSlug)->firstOrFail();
        $nearbyGroups = Group::where('program_id', $program->id)
            ->near(Auth::user()->addresses->first())
            ->with('meetingAddress')
            ->limit(6)
            ->get();

        return view('seasons.registration.select-group')
            ->with('program', $program)
            ->with('nearbyGroups', $nearbyGroups);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function chooseGroup($programSlug, $groupId)
    {
        /** @var GroupRegistration $registration */
        $registration = Session::seasonalGroupRegistration();
        $registration->addGroup(Group::findOrFail($groupId));

        Session::setSeasonalGroupRegistration($registration);

        return redirect('/register/summary');
    }

    /**
     * Remember the group the user is trying to register for
     */
    public function rememberGroup($guid)
    {
        Session::setGroupToRegisterWith($guid);

        return redirect('/');
    }

    /**
     * Submit the player's seasonal registration
     */
    public function submit(GroupRegistrar $groupRegistrar)
    {
        $groupRegistrar->register(
            Session::season(),
            Auth::user(),
            Session::seasonalGroupRegistration()
        );

        return redirect('/dashboard')->withFlashSuccess('Your registration has been submitted!');
    }

    /**
     * If the group isn't found this step will direct
     * the parent to a later
     */
    public function later($programSlug)
    {
        /** @var GroupRegistration $registration */
        $registration = Session::seasonalGroupRegistration();

        $playersRemovedFromProgram = null;
        $continueRegistration = false;
        foreach (Program::all() as $program) {
            // don't register players in this program
            if ($program->slug == $programSlug && $registration->numberOfPlayers($program) > 0) {
                $registration->removePlayers($program);
                $playersRemovedFromProgram = $program;
            }

            // only continue if other programs have players
            if ($registration->numberOfPlayers($program) > 0) {
                $continueRegistration = true;
            }
        }

        Session::setSeasonalGroupRegistration($registration);

        if ($continueRegistration) {
            return redirect('/register/summary')
                ->withFlashSuccess('Your '.$playersRemovedFromProgram->abbreviation.' players have been removed from this registration');
        }

        return redirect('/dashboard');
    }

    public static function viewBindings()
    {
        \View::creator('seasons.registration.partials.group-search', function (View $view) {
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
            $view->with('players', Auth::user()
                ->players()
                ->notRegisteredWithNBB(Session::season(), Auth::user())
                ->get());
        });
    }
}
