<?php namespace BibleBowl\Http\Controllers\Tournaments;

use BibleBowl\Event;
use BibleBowl\EventType;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Admin\Controller;
use BibleBowl\Http\Requests\TournamentCreatorOnlyRequest;
use BibleBowl\Tournament;

class EventsController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function create($tournamentId)
    {
        return view('tournaments.events.create')
                ->withTournament(Tournament::findOrFail($tournamentId))
                ->with('eventTypes', EventType::orderBy('name', 'ASC')->get());
    }

    /**
     * @return mixed
     */
    public function store(TournamentCreatorOnlyRequest $request, $tournamentId)
    {
        $request->merge([
            'tournament_id' => $tournamentId
        ]);
        $this->validate($request, EventType::validationRules());

        Event::create($request->except('_token'));

        return redirect('/tournaments/'.$tournamentId)->withFlashSuccess('Event has been created');
    }

    /**
     * @param TournamentCreatorOnlyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(TournamentCreatorOnlyRequest $request, $tournamentId, $eventId)
    {
        return view('tournaments.events.edit')
            ->withTournament(Tournament::findOrFail($tournamentId))
            ->withEvent(Event::findOrFail($eventId));
    }

    /**
     * @param TournamentCreatorOnlyRequest  $request
     * @param                               $id
     *
     * @return mixed
     */
    public function update(TournamentCreatorOnlyRequest $request, $tournamentId, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->update($request->except('_token', '_method'));

        return redirect('/tournaments/'.$tournamentId)->withFlashSuccess('Your changes were saved');
    }

    public function destroy($tournamentId, $eventId)
    {
        Event::findOrFail($eventId)->delete();

        return redirect('/tournaments/'.$tournamentId)->withFlashSuccess('Event deleted');
    }
}