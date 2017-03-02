<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamSetUpdateRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Team;
use DB;

class TeamController extends Controller
{
    /**
     * @param   $request
     * @param   $id
     *
     * @return mixed
     */
    public function store(TeamSetUpdateRequest $request)
    {
        $request->merge([
            'team_set_id' => $request->route('teamset'),
        ]);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $team = Team::create($request->except('_token'));

        return response()->json($team);
    }

    /**
     * @param   $request
     * @param   $id
     *
     * @return mixed
     */
    public function update(TeamUpdateRequest $request)
    {
        $request->team()->update([
            'name' => $request->input('name'),
        ]);

        return response()->json();
    }

    /**
     * @param   $request
     * @param   $id
     *
     * @return mixed
     */
    public function destroy(TeamUpdateRequest $request)
    {
        $request->team()->delete();

        return response()->json();
    }

    /**
     * @param   $request
     * @param   $id
     *
     * @return mixed
     */
    public function addPlayer(TeamUpdateRequest $request, $id)
    {
        // prevent duplicates
        if ($request->team()->players()->where('player_id', $request->get('playerId'))->count() == 0) {
            $request->team()->players()->attach($request->get('playerId'));
        }

        return response()->json();
    }

    /**
     * @param   $request
     * @param   $id
     *
     * @return mixed
     */
    public function removePlayer(TeamUpdateRequest $request, $id)
    {
        $request->team()->players()->detach($request->get('playerId'));

        return response()->json();
    }

    /**
     * Update the order of players on a team.
     *
     * @param   $request
     * @param   $id
     *
     * @return mixed
     */
    public function updateOrder(TeamUpdateRequest $request, $id)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->input('sortOrder') as $index => $playerId) {
                $request->team()->players()->updateExistingPivot($playerId, [
                    'order' => $index,
                ]);
            }
        });

        return response()->json();
    }
}
