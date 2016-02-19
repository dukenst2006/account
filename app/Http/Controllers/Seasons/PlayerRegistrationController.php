<?php namespace BibleBowl\Http\Controllers\Seasons;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\GroupJoinRequest;
use BibleBowl\Http\Requests\PlayerRegistrationRequest;
use BibleBowl\Http\Requests\Request;
use BibleBowl\Http\Requests\SeasonRegistrationRequest;
use BibleBowl\Program;
use BibleBowl\Season;
use BibleBowl\Seasons\ProgramRegistrationPaymentReceived;
use BibleBowl\Seasons\SeasonalRegistration;
use BibleBowl\Seasons\SeasonalRegistrationPaymentReceived;
use Illuminate\View\View;
use Input;
use Session;
use Cart;

class PlayerRegistrationController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getPlayers()
    {
        $season = Season::current()->first();
        return view('seasons.registration.players')
            ->withSeason($season)
            ->withPlayers(Auth::user()
                ->players()
                ->notRegisteredWithNBB($season, Auth::user())
                ->get());
    }

    /**
     * @return mixed
     */
    public function postPlayers(
        PlayerRegistrationRequest $request,
        SeasonalRegistration $registration,
        SeasonalRegistrationPaymentReceived $seasonalRegistrationPaymentReceived
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
        if (is_null($familiarGroup) === false) {
            $registration->setGroup($familiarGroup->program, $familiarGroup);
        }

        // save in session so we can show this info on
        // the summary page
        Session::setSeasonalRegistration($registration);

        // Add the compiled registration information to the cart
        // so it can be processed once payment has gone through
        $cart = Cart::clear();
        $seasonalRegistrationPaymentReceived->setRegistration($registration);
        $cart->setPostPurchaseEvent($seasonalRegistrationPaymentReceived)->save();
        foreach ($registration->programs() as $program) {
            $cart->add(
                $program->sku,
                $program->registration_fee,
                $registration->numberOfPlayers($program)
            );
        }

        return redirect('/register/summary');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function summary()
    {
        return view('seasons.registration.summary')
            ->withRegistration(Session::seasonalRegistration());
    }

    /**
     * @return \Illuminate\View\View
     */
    public function findGroupToRegister($programSlug)
    {
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
     * @return \Illuminate\View\View
     */
    public function chooseGroup($programSlug, $groupId = null)
    {
        /** @var SeasonalRegistration $registration */
        $registration = Session::seasonalRegistration();
        $program = Program::where('slug', $programSlug)->firstOrFail();

        if ($groupId == null) {
            $registration->noGroupFound($program);
        } else {
            $registration->setGroup(
                $program,
                Group::findOrFail($groupId)
            );
        }

        Session::setSeasonalRegistration($registration);

        return redirect('/register/summary');
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
    private function findNearbyGroups(Program $program)
    {
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

    public function getPayPlayerRegistration()
    {
        $group = Session::group();
        return view('seasons.registration.pay', [
            'group' => $group,
            'players' => $group->players()->pendingRegistrationPayment()->get()
        ]);
    }

    public function postPayPlayerRegistration(GroupCreatorOnlyRequest $request, ProgramRegistrationPaymentReceived $programRegistrationPaymentReceived)
    {
        $this->validate($request, [
            'player' => 'required'
        ], [
            'player.required' => 'You must select at least one player to proceed'
        ]);

        $programRegistrationPaymentReceived->setPlayers(collect(array_keys($request->get('player'))));

        $cart = Cart::clear();
        $cart->setPostPurchaseEvent($programRegistrationPaymentReceived)->save();

        $group = Session::group();
        $cart->add(
            $group->program->sku,
            $group->program->registration_fee,
            count($request->get('player'))
        );

        return redirect('/cart');
    }
}
