<?php namespace BibleBowl\Location;

use BibleBowl\Group;
use BibleBowl\Location\Maps\Map;
use BibleBowl\Location\Maps\Location;
use DB;
use BibleBowl\Season;
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
    protected $description = "Sync the group map on the wordpress site";

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
        foreach($groups as $group) {
            /** @var Location $location */
            $location = $group->wordpressLocation();

            if ($group->isInactive()) {
                $location->delete();
            } else {
                $activeLocations[] = $location->location_id;
                $location->updateMarkerInformation($group);
                $location->save();
            }
        }
        
        $map->map_locations = $activeLocations;
        $map->save();

        DB::commit();
    }
}
