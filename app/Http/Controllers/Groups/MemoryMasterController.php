<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeadCoachOnlyRequest;
use Carbon\Carbon;
use DB;
use Session;
use Setting;

class MemoryMasterController extends Controller
{
    public function showAchievers()
    {
        $group = Session::group();
        $season = Session::season();

        return view('group.roster.memory-master')
            ->withPlayers($group->players)
            ->with('group', $group)
            ->with('season', $season)
            ->with('tooLateToSubmit', Setting::memoryMasterDeadline()->lt(Carbon::now()))
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
            $season->id,
        ]);

        // flag certain players as memory masters
        DB::update('UPDATE player_season SET memory_master = 1 WHERE group_id = ? AND season_id = ? AND player_id IN('.implode(',', array_keys($request->get('player'))).')', [
            $group->id,
            $season->id,
        ]);

        DB::commit();

        return redirect()->back()->withFlashSuccess('Your changes have been saved');
    }
}
