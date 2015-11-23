<?php namespace BibleBowl\Http\Controllers\Admin\Tournaments;

use Auth;
use BibleBowl\Competition\TournamentCreator;
use BibleBowl\Group;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Http\Requests\TournamentCreateRequest;
use BibleBowl\Http\Requests\TournamentCreatorOnlyRequest;
use BibleBowl\Tournament;
use Session;

class EventsController extends Controller
{

//    public function show($tournamentId)
//    {
//        return view('/admin/tournaments/events/show', [
//            'tournament' => Tournament::findOrFail($tournamentId)
//        ]);
//    }


	/**
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('admin.tournaments.events.create');
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
	public function update(GroupEditRequest $request, $id)
	{
		$group = Group::findOrFail($id);
		$form = $request->all();

		// When the user has not checked the "inactive" checkbox.
		if (!$request->has('inactive')) {
			// Group is Active.
			$form['inactive'] = null;
		}

		$group->update($form);

		// update the user's session
		if (Session::group()->id == $group->id) {
			Session::setGroup($group);
		}
		return redirect('/group/'.$group->id.'/edit')->withFlashSuccess('Your changes were saved');
	}

	/**
	 * Swap the current user's group for another
	 *
	 * @param GroupCreatorOnlyRequest $request
	 * @param                         $id
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function swap(GroupCreatorOnlyRequest $request, $id)
	{
		Session::setGroup(Group::findOrFail($id));

		return redirect('/dashboard');
	}

}
