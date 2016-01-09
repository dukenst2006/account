<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupCreator;
use BibleBowl\Http\Requests\GroupCreationRequest;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Program;
use Input;
use Session;

class GroupController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function searchBeforeCreate()
    {
        $groups = [];
        if (Input::has('q')) {
            $groups = Group::where('name', 'LIKE', '%'.Input::get('q').'%')
                ->with('meetingAddress', 'program')
                ->orderBy('name', 'ASC')
                ->get();
        }

        return view('/group/create-search', [
            'groups' => $groups
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $programs = [];
        foreach (Program::all() as $program) {
            $programs[$program->id] = $program.'';
        }

        return view('group.create')
            ->withPrograms($programs);
    }

    /**
     * @return mixed
     */
    public function store(GroupCreationRequest $request, GroupCreator $groupCreator)
    {
        $group = $groupCreator->create(Auth::user(), $request->except([
            'amHeadCoach'
        ]));

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
