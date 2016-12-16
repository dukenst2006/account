<?php

namespace BibleBowl\Http\Controllers\Tournaments\Admin;

use Auth;
use BibleBowl\Event;
use BibleBowl\EventType;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\TournamentCreatorOnlyRequest;
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Tournament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\CellWriter;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class EventsController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function create($tournamentId)
    {
        return view('tournaments.admin.events.create')
                ->withTournament(Tournament::findOrFail($tournamentId))
                ->with('eventTypes', EventType::orderBy('name', 'ASC')->with('participantType')->get());
    }

    /**
     * @return mixed
     */
    public function store(TournamentCreatorOnlyRequest $request, $tournamentId)
    {
        $request->merge([
            'tournament_id' => $tournamentId,
        ]);
        $this->validate($request, EventType::validationRules());

        Event::create($request->except('_token'));

        return redirect('/admin/tournaments/'.$tournamentId)->withFlashSuccess('Event has been created');
    }

    /**
     * @param TournamentCreatorOnlyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(TournamentCreatorOnlyRequest $request, $tournamentId, $eventId)
    {
        return view('tournaments.admin.events.edit')
            ->withTournament(Tournament::findOrFail($tournamentId))
            ->withEvent(Event::findOrFail($eventId));
    }

    /**
     * @param TournamentCreatorOnlyRequest $request
     * @param                              $id
     *
     * @return mixed
     */
    public function update(TournamentCreatorOnlyRequest $request, $tournamentId, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->update($request->except('_token', '_method'));

        return redirect('/admin/tournaments/'.$tournamentId)->withFlashSuccess('Your changes were saved');
    }

    public function destroy($tournamentId, $eventId)
    {
        Event::findOrFail($eventId)->delete();

        return redirect('/admin/tournaments/'.$tournamentId)->withFlashSuccess('Event deleted');
    }

    public function exportParticipants(Request $request, int $tournamentId, int $eventId, string $format, Excel $excel)
    {
        $season = Season::current()->firstOrFail();
        $tournament = Tournament::findOrFail($tournamentId);
        $event = Event::findOrFail($eventId);

        // get some info about them this season
        $players = $event->eligiblePlayers()
            ->with([
                'seasons' => function ($q) use ($season) {
                    $q->where('seasons.id', $season->id);
                },
                'groups' => function ($q) use ($season) {
                    $q->where('player_season.season_id', $season->id);
                }
            ])
            ->get();

        $document = $excel->create($tournament->slug.'_'.str_slug($event->type->name).'_participants', function(LaravelExcelWriter $excel) use ($players) {

            $excel->sheet('Players', function(LaravelExcelWorksheet $sheet) use ($players) {

                $sheet->appendRow([
                    'Last Name',
                    'First Name',
                    'Gender',
                    'Grade',
                    'Group',
                    'Signed Up',
                ]);

                /** @var Player $player */
                foreach ($players as $player) {
                    $sheet->appendRow([
                        $player->last_name,
                        $player->first_name,
                        $player->gender,
                        $player->seasons->first()->pivot->grade,
                        $player->groups->first()->name,
                        $player->pivot->created_at->timezone(Auth::user()->settings->timeszone())->toDateTimeString(),
                    ]);
                }

            });

        });

        if (app()->environment('testing')) {
            echo $document->string('csv');
        } else {
            $document->download($format);
        }
    }
}
