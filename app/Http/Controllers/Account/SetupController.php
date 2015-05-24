<?php namespace BibleBowl\Http\Controllers\Account;

use App;
use BibleBowl\Support\Scrubber\Scrubber;
use DB;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use BibleBowl\Address;
use BibleBowl\Http\Controllers\Controller;

class SetupController extends Controller
{
	/** @var Scrubber */
	protected $scrubber;

	public function __construct(Scrubber $scrubber)
	{
		$this->scrubber = $scrubber;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getSetup(Request $request)
	{
		return view('account.setup');
	}

	/**
	 * @return mixed
	 */
	public function postSetup(Request $request)
	{
		$request->merge([
			'name' 	=> 'Home', //default address name,
			'phone' => $this->scrubber->integer($request->get('phone')) //strip non-int characters
		]);

		$this->validate($request, array_merge(Address::validationRules(), [
			'phone'		=> 'required|integer|digits:10',
			'gender'	=> 'required'
		]), Address::validationMessages());

		//update the info
		DB::transaction(function () use($request) {
			$user = Auth::user();
			$user->first_name = $request->get('first_name');
			$user->last_name = $request->get('last_name');
			$user->phone = $request->get('phone');
			$user->gender = $request->get('gender');
			$user->save();

			$address = App::make('BibleBowl\Address', [$request->except([
				'phone', 'gender'
			])]);
			$user->addresses()->save($address);
		});

		return redirect('/dashboard');
	}

}
