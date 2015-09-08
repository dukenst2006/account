<?php namespace BibleBowl\Http\Controllers\Groups;

use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Session;
use Str;

class RosterController extends Controller
{

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$group = Session::group();
		$season = Session::season();

		return view('group.roster')
			->withActivePlayers($group->players()->active($season)->with('guardian')->get())
			->withInactivePlayerCount($group->players()->inactive($season)->count());
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function inactive()
	{
		$group = Session::group();

		return view('group.roster.inactive')
			->withInactivePlayers($group->players()->inactive(Session::season())->with('guardian')->get());
	}

    /**
     * @return \Illuminate\View\View
     */
    public function map()
    {
        $group = Session::group();
        $season = Session::season();

        // eager load the active players for each guardian
        $guardians = $group->guardians($season)
            ->with([
                'primaryAddress',
                'players' => function (HasMany $q) use ($season, $group) {
                    $q->join('player_season', 'player_season.player_id', '=', 'players.id')
                        ->active($season);
                        $q->whereHas('groups', function (Builder $q) use ($group) {
                            $q->where('groups.id', $group->id);
                        });
                }
            ])
            ->get();

        return view('group.roster.map')
            ->withGroup($group)
            ->with('meetingAddress',$group->meetingAddress)
            ->withGuardians($guardians);
    }

	public function export()
	{
		$group = Session::group();
		$players = $group->players()->active(Session::season())->with('guardian')->get();

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="'.str_replace(' ', '_', $group->shortType().' Roster').'-'.str_replace(' ', '_', $group->name).'-'.date("m.d.y").'.csv"');

		$output = fopen('php://output', 'w');

		fputcsv($output, [
			'First Name',
			'Last Name',
			'Grade',
			'Gender',
			'Age',
			'Birthday',
			'T-Shirt Size',
			'Parent/Guardian',
			'Email',
			'Address',
			'Phone'
		]);

		foreach ($players as $player) {
			fputcsv($output, array(
				$player->first_name,
				$player->last_name,
				$player->pivot->grade,
				$player->gender,
				$player->age(),
				$player->birthday->toDateString(),
				$player->pivot->shirt_size,
				$player->guardian->full_name,
				$player->guardian->email,
				$player->guardian->address,
				$player->guardian->phone
			));
		}

		fclose($output);
		exit;
	}

}
