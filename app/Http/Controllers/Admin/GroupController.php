<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Group;
use Input;
use Session;

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
        $season = Session::season();
        $group = Group::findOrFail($groupId);
        return view('/admin/groups/show', [
            'group'                 => $group,
            'season'                => $season,
            'activePlayers'         => $group->players()->with('guardian')->active($season)->get(),
            'inactivePlayers'       => $group->players()->with('guardian')->inactive($season)->get(),
            'pendingPaymentPlayers' => $group->players()->with('guardian')->pendingRegistrationPayment($season)->get()
        ]);
    }
}
