<?php

namespace BibleBowl\Http\Controllers\Groups;

use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\HeadCoachOnlyRequest;
use DB;
use Session;

class MemoryMasterController extends Controller
{
    public function showAchievers()
    {
        $group = Session::group();
        $season = Session::season();

        return view('group.roster.memory-master')
            ->withPlayers($group->players)
            ->with('playersWhoAchieved', $group->players()->achievedMemoryMaster($season)->get()->modelKeys());
    }

    public function updateAchievers(HeadCoachOnlyRequest $request)
    {
        $group = Session::group();
        $season = Session::season();

        DB::beginTransaction();

        // remove all memory masters
        DB::update('UPDATE player_season SET memory_master = 0 WHERE group_id = ? AND season_id = ?', [
            $group->id,
            $season->id
        ]);

        // flag certain players as memory masters
        DB::update('UPDATE player_season SET memory_master = 1 WHERE group_id = ? AND season_id = ? AND player_id IN('.implode(',', array_keys($request->get('player'))).')', [
            $group->id,
            $season->id
        ]);

        DB::commit();

        return redirect()->back()->withFlashSuccess('Your changes have been saved');
    }
}
