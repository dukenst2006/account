<?php

namespace App\Http\Controllers\Tournaments\Admin\Registrations;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Tournament;

class GroupsController extends Controller
{
    public function index(Request $request, Tournament $tournament)
    {
        return view('tournaments.admin.registrations.groups.index', [
            'tournament'  => $tournament,
            'groups'      => $tournament->eligibleGroups()
                ->select(
                    'groups.*'
                )
                ->where('groups.name', 'LIKE', '%'.$request->get('q').'%')
                ->paginate(25)
                ->appends($request->only('q')),
        ]);
    }

    public function show(Tournament $tournament, Group $group)
    {
        $teamSet = $tournament->teamSet($group);

        return view('tournaments.admin.registrations.groups.show', [
            'tournament'  => $tournament,
            'group'       => $group,
            'teamSet'     => $teamSet,
            'teamCount'   => $teamSet == null ? 0 : $teamSet->players()->count(),
            'playerCount' => $teamSet == null ? 0 : $teamSet->players()->count(),
            'quizmasters' => $tournament->tournamentQuizmasters()->with('user')->where('group_id', $group->id)->orderBy('receipt_id', 'ASC')->get(),
            'spectators'  => $tournament->spectators()->with('minors', 'user')->where('group_id', $group->id)->orderBy('receipt_id', 'ASC')->get(),
        ]);
    }
}
