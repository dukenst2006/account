<?php namespace BibleBowl\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Http\Requests\GroupCreationRequest;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Group;
use BibleBowl\Groups\GroupCreator;

class GroupController extends Controller
{

	/**
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('group.create');
	}

	/**
	 * @return mixed
	 */
	public function store(GroupCreationRequest $request, GroupCreator $groupCreator)
	{
		$group = $groupCreator->create(Auth::user(), $request->all());

		// log the user in under this group
		Session::setGroup($group);

		return redirect('/dashboard')->withFlashSuccess($group->name.' has been created');
	}

	/**
	 * @param GroupCreatorOnlyRequest $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function edit(GroupCreatorOnlyRequest $request, $id)
	{
		return view('group.edit')
			->withGroup(Group::findOrFail($id));
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

        // If the inactive checkbox is unchecked.
		if ($request->inactive === null) {
			// Group is Active.
			$form['inactive'] = null;
		}

		$group->update($form);

		// update the user's session
		if (Session::group()->id == $group->id) {
			Session::setGroup($group);
		}
		return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
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
