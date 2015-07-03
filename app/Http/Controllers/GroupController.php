<?php namespace BibleBowl\Http\Controllers;

use Session;
use Auth;
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
	 * @param GuardianOnlyRequest 	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function update(GroupCreatorOnlyRequest $request, $id)
	{
		$this->validate($request, Group::validationRules());

		//Player::findOrFail($id)->update($request->all());

		return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
	}

}
