<?php namespace BibleBowl\Http\Controllers\Admin;

use Auth;
use BibleBowl\Competition\TournamentCreator;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Http\Requests\TournamentCreateRequest;
use BibleBowl\Http\Requests\TournamentCreatorOnlyRequest;
use BibleBowl\Http\Requests\TournamentEditRequest;
use BibleBowl\Tournament;
use Session;

class TournamentsController extends Controller
{

    public function index()
    {
        return view('/admin/tournaments/index', [
            'tournaments' => Tournament::where('season_id', Session::season()->id)
                ->where('creator_id', Auth::user()->id)
                ->orderBy('season_id', 'DESC')
                ->orderBy('start', 'DESC')
                ->paginate(25)
        ]);
    }

    public function show($tournamentId)
    {
        return view('/admin/tournaments/show', [
            'tournament' => Tournament::findOrFail($tournamentId)
        ]);
    }


    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tournaments.create');
    }

    /**
     * @return mixed
     */
    public function store(TournamentCreateRequest $request, TournamentCreator $tournamentCreator)
    {
        $tournament = $tournamentCreator->create(
            Auth::user(),
            Session::season(),
            $request->all()
        );

        return redirect('/admin/tournaments')->withFlashSuccess($tournament->name.' has been created');
    }

    /**
     * @param TournamentCreatorOnlyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(TournamentCreatorOnlyRequest $request, $id)
    {
        return view('admin.tournaments.edit')
            ->withTournament(Tournament::findOrFail($id));
    }

    /**
     * @param GroupEditRequest 		$request
     * @param                     	$id
     *
     * @return mixed
     */
    public function update(TournamentEditRequest $request, $id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournament->update($request->all());

        return redirect('/admin/tournaments/'.$id)->withFlashSuccess('Your changes were saved');
    }
}
