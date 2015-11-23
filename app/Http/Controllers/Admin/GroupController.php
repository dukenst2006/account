<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Group;
use Input;

class GroupController extends Controller
{

    public function index()
    {
        $groups = Group::where('name', 'LIKE', '%'.Input::get('q').'%')
            ->with('owner', 'program')
            ->orderBy('name', 'ASC')
            ->paginate(25);

        return view('/admin/groups/index', [
            'groups' => $groups->appends(Input::only('q'))
        ]);
    }

    public function show($groupId)
    {
        return view('/admin/groups/show', [
            'group' => Group::findOrFail($groupId)
        ]);
    }

}
