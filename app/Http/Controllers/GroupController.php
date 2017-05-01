<?php

namespace App\Http\Controllers;

use App\Group;
use App\Groups\GroupCreator;
use App\Groups\Settings;
use App\Http\Requests\GroupCreationRequest;
use App\Http\Requests\GroupCreatorOnlyRequest;
use App\Http\Requests\GroupEditRequest;
use App\Http\Requests\GroupHeadCoachOnlyRequest;
use App\Program;
use Auth;
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
        if ($hasSearched = Input::has('q')) {
            $groups = Group::where('name', 'LIKE', '%'.Input::get('q').'%')
                ->with('meetingAddress', 'program')
                ->orderBy('name', 'ASC')
                ->get();
        }

        return view('/group/create-search', [
            'groups'        => $groups,
            'hasSearched'   => $hasSearched,
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
            'amHeadCoach',
        ]));

        // log the user in under this group
        Session::setGroup($group);

        // direct the user to their email settings so
        // they can customize their welcome email
        return redirect('/group/'.$group->id.'/settings/email?justCreated=1')->withFlashSuccess($group->name.' has been created');
    }

    /**
     * @param GroupCreatorOnlyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(GroupCreatorOnlyRequest $request, $id)
    {
        $group = Group::findOrFail($id);

        return view('group.edit')
            ->withGroup($group)
            ->withSettings($group->settings);
    }

    /**
     * @param GroupEditRequest $request
     * @param                  $id
     *
     * @return mixed
     */
    public function update(GroupEditRequest $request, $id)
    {
        $group = Group::findOrFail($id);
        $form = $request->except('group_id');

        // When the user has not checked the "inactive" checkbox.
        if (!$request->has('inactive')) {
            // Group is Active.
            $form['inactive'] = null;
        }

        /** @var Settings $settings */
        $settings = $group->settings;
        if ($request->has('group_id')) {
            $settings->setGroupToShareRosterWith(Group::findOrFail($request->get('group_id')));
        }
        $form['settings'] = $settings;

        $group->update($form);

        // update the user's session
        if (Session::group()->id == $group->id) {
            Session::setGroup($group);
        }

        return redirect('/group/'.$group->id.'/edit')->withFlashSuccess('Your changes were saved');
    }

    /**
     * Swap the current user's group for another.
     *
     * @param GroupCreatorOnlyRequest $request
     * @param                         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function swap(GroupHeadCoachOnlyRequest $request, $id)
    {
        Session::setGroup(Group::findOrFail($id));

        return redirect('/dashboard');
    }
}
