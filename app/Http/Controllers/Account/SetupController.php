<?php namespace BibleBowl\Http\Controllers\Account;

use App;
use BibleBowl\Support\Scrubber;
use BibleBowl\User;
use DB;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use BibleBowl\Address;
use BibleBowl\Http\Controllers\Controller;

class SetupController extends Controller
{
	/**
	 * @return \Illuminate\View\View
	 */
	public function getSetup()
	{
		return view('account.setup');
	}

	/**
	 * @return mixed
	 */
	public function postSetup(Request $request, Scrubber $scrubber)
	{
		$request->merge([
			'name' 	=> 'Home', //default address name,
			'phone' => $scrubber->integer($request->get('phone')) //strip non-int characters
		]);

		$userRules = array_only(User::validationRules(), ['gender', 'phone']);
		$this->validate($request, array_merge(Address::validationRules(), $userRules), Address::validationMessages());

		//update the info
		DB::transaction(function () use($request) {
			$user = Auth::user();
			$user->first_name = $request->get('first_name');
			$user->last_name = $request->get('last_name');
			$user->phone = $request->get('phone');
			$user->gender = $request->get('gender');
			$user->save();

			$address = App::make(Address::class, [$request->except([
				'first_name', 'last_name', 'phone', 'gender'
			])]);
			$user->addresses()->save($address);
		});

		return redirect('/dashboard');
	}

}
