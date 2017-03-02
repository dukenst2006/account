<?php

namespace App\Location;

use App\Group;
use App\Location\Maps\Location;
use App\Location\Maps\Map;
use DB;
use Illuminate\Console\Command;

class GroupMapUpdater extends Command
{
    const COMMAND = 'biblebowl:sync-group-map';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = self::COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the group map on the wordpress site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        /** @var Group[] $groups */
        $groups = Group::active()->with('owner', 'meetingAddress')->get();

        DB::beginTransaction();

        $map = Map::findOrFail(Map::GROUPS);

        $activeLocations = [];
        foreach ($groups as $group) {
            /** @var Location $location */
            $location = $group->wordpressLocation();

            if ($group->isInactive()) {
                $location->delete();
            } else {
                if ($location == null) {
                    $location = app(Location::class);
                }
                $location->updateMarkerInformation($group);
                $location->save();

                $activeLocations[] = $location->location_id;
            }
        }

        $map->map_locations = $activeLocations;
        $map->save();

        DB::commit();
    }
}
