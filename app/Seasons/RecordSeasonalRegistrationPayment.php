<?php

namespace App\Seasons;

use App\Group;
use App\Player;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Session;

class RecordSeasonalRegistrationPayment
{
    /**
     * @param Player[] $players
     */
    public function handle(Collection $players)
    {
        $playerIds = implode(',', $players->pluck('id')->toArray());
        $now = Carbon::now();
        DB::update('UPDATE player_season SET paid = ? WHERE group_id = ? AND player_id IN('.$playerIds.')', [
            $now->toDateTimeString(),
            Session::group()->id,
        ]);
    }
}
