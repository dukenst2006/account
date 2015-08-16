<?php namespace BibleBowl\Http\Controllers\Groups;

use Str;
use BibleBowl\Http\Controllers\Controller;
use Session;
use BibleBowl\Group;

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

		return view('group.roster')
			->withInactivePlayers($group->players()->inactive()->with('guardian')->get());
	}

	public function export()
	{
		$group = Session::group();
		$players = $group->players()->active(Session::season())->with('guardian')->get();

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="'.str_replace(' ', '_', $group->type().' Roster').'-'.str_replace(' ', '_', $group->name).'-'.date("m.d.y").'.csv"');

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
