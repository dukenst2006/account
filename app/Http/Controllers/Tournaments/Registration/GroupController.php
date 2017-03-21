<?php

namespace App\Http\Controllers\Tournaments\Registration;

use App\Competition\Teams\Duplicater;
use App\Competition\Tournaments\Groups\RegistrationPaymentReceived;
use App\Event;
use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeadCoachOnlyRequest;
use App\TeamSet;
use App\Tournament;
use Cart;
use DB;
use Session;

class GroupController extends Controller
{
    public function index(HeadCoachOnlyRequest $request, $slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();
        $group = Session::group();

        return view('tournaments.registration.group-overview', [
            'tournament'                => $tournament,
            'group'                     => $group,
            'teamSet'                   => $teamSet = $group->teamSet($tournament),
            'teamCount'                 => $teamSet == null ? 0 : $teamSet->teams()->count(),
            'playerCount'               => $teamSet == null ? 0 : $teamSet->players()->count(),
            'individualEventCount'      => $tournament->individualEvents()->withOptionalParticipation()->count(),

            // show unpaid first, then paid
            'quizmasters'               => $tournament->tournamentQuizmasters()->with('user')->where('group_id', $group->id)->orderBy('receipt_id', 'ASC')->get(),
            'spectators'                => $tournament->spectators()->with('minors', 'user')->where('group_id', $group->id)->orderBy('receipt_id', 'ASC')->get(),
        ]);
    }

    public function chooseTeams(HeadCoachOnlyRequest $request, $slug)
    {
        $group = Session::group();
        $teamSets = $group->teamSets()->season(Session::season())->get();
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        if (count($teamSets) == 0) {
            $teamSet = $this->findOrCreateTeamSetForTournament($tournament, $group);

            return redirect('/teamsets/'.$teamSet->id);
        }

        return view('tournaments.registration.choose-teams', [
            'tournament'    => $tournament,
            'teamSets'      => $teamSets,
        ]);
    }

    public function newTeamSet(HeadCoachOnlyRequest $request, $slug)
    {
        $group = Session::group();
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $teamSet = $this->findOrCreateTeamSetForTournament($tournament, $group);

        return redirect('/teamsets/'.$teamSet->id);
    }

    public function setTeamSet(HeadCoachOnlyRequest $request, $slug, TeamSet $teamSet, Duplicater $duplicater)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        // duplicate
        DB::beginTransaction();
        $newTeamSet = $duplicater->duplicate($teamSet, [
            'tournament_id',
        ]);
        $newTeamSet->update([
            'name'          => $tournament->name.' Teams',
            'tournament_id' => $tournament->id,
        ]);
        DB::commit();

        return redirect('tournaments/'.$slug.'/registration/group/events');
    }

    public function events(HeadCoachOnlyRequest $request, $slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        // if there aren't any events, back to group summary
        if ($tournament->individualEvents()->withOptionalParticipation()->count() == 0) {
            return redirect('tournaments/'.$slug.'/group');
        }

        $teamSet = $tournament->teamSet(Session::group());

        return view('tournaments.registration.events', [
            'tournament'    => $tournament,
            'events'        => $tournament->individualEvents()->withOptionalParticipation()->with('type')->get(),
            'players'       => $playerCount = $teamSet->players()->get(),
            'playerCount'   => count($playerCount),
        ]);
    }

    public function postEvents(HeadCoachOnlyRequest $request, $slug)
    {
        $tournament = Tournament::where('slug', $slug)
            ->with('events')
            ->with('events.players')
            ->firstOrFail();

        DB::beginTransaction();

        foreach ($request->get('event', []) as $eventId => $players) {
            /** @var Event $event */
            $event = $tournament->events->find($eventId);

            // sync preserves receipt_id unless a player is removed
            $event->players()->sync(array_keys($players));
        }

        DB::commit();

        return redirect('tournaments/'.$slug.'/group');
    }

    public function pay(
        HeadCoachOnlyRequest $request,
        $slug,
        RegistrationPaymentReceived $groupRegistrationPaymentReceived
    ) {
        $group = Session::group();
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $playersWithUnpaidSeasonalFees = $tournament->teamSet($group)->players()->pendingRegistrationPayment($tournament->season)->get();
        if ($playersWithUnpaidSeasonalFees->count() > 0) {
            return redirect()->back()->withErrors('The following player(s) still have outstanding seasonal registration fees: '.implode(',', $playersWithUnpaidSeasonalFees->pluck('full_name')));
        }

        $unpaidTeamCount = $tournament->teamSet($group)->teams()->unpaid()->count();
        $spotsLeft = $tournament->teamSpotsLeft();
        if ($unpaidTeamCount > $spotsLeft) {
            return redirect()->back()->withErrors('There are only '.$spotsLeft.' team slots left.  Reduce your number of teams and try again.');
        }

        $ineligibleTeamCount = $tournament->teamSet($group)->teams()->withoutEnoughPlayers($tournament)->unpaid()->count();
        if ($ineligibleTeamCount > 0) {
            return redirect()->back()->withErrors($ineligibleTeamCount.' team(s) must be updated to have between '.$tournament->settings->minimumPlayersPerTeam().'-'.$tournament->settings->maximumPlayersPerTeam().' players before you can submit payment.');
        }

        // require quizmaster counts to be up to speed before proceeding
        $quizmasterCount = $tournament->tournamentQuizmasters()->where('group_id', $group->id)->count();
        if ($tournament->settings->shouldRequireQuizmastersByGroup() && $quizmasterCount < $tournament->settings->quizmastersToRequireByGroup()) {
            return redirect()->back()->withErrors('You need to register '.$tournament->settings->quizmastersToRequireByGroup().' quizmaster(s) before you can proceed.');
        } elseif ($tournament->settings->shouldRequireQuizmastersByTeamCount()) {
            $teamCount = $tournament->teamSet($group)->teams()->count();
            $numberOfQuizmastersRequired = $tournament->numberOfQuizmastersRequiredByTeamCount($teamCount);
            if ($quizmasterCount < $numberOfQuizmastersRequired) {
                return redirect()->back()->withErrors('Because you have '.$teamCount.' team(s), you need '.$numberOfQuizmastersRequired.' quizmaster(s) before you can proceed.');
            }
        }

        // build cart and redirect
        $groupRegistration = $tournament->eligibleRegistrationWithOutstandingFees($group);
        $groupRegistrationPaymentReceived->setGroupRegistration($groupRegistration);

        $cart = Cart::clear();
        $cart->setPostPurchaseEvent($groupRegistrationPaymentReceived)->save();
        $groupRegistration->populateCart($cart);

        return redirect('/cart');
    }

    protected function findOrCreateTeamSetForTournament(Tournament $tournament, Group $group) : TeamSet
    {
        $existingTeamSet = TeamSet::where([
            'group_id'      => $group->id,
            'tournament_id' => $tournament->id,
        ])->orWhere([
            'group_id'      => $group->id,
            'season_id'     => $tournament->season_id,
            'name'          => $tournament->name.' Teams',
        ])->first();

        if ($existingTeamSet != null && $existingTeamSet->exists) {
            return $existingTeamSet;
        }

        return TeamSet::create([
            'group_id'      => $group->id,
            'season_id'     => $tournament->season_id,
            'tournament_id' => $tournament->id,
            'name'          => $tournament->name.' Teams',
        ]);
    }
}
