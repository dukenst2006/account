<?php namespace BibleBowl\Http\Controllers\Account;

use Auth;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Support\Scrubber;
use BibleBowl\User;
use Illuminate\Http\Request;
use Redirect;

class AccountController extends Controller
{

	/**
	 * @return \Illuminate\View\View
	 */
	public function edit()
	{
		return view('account.edit')->withUser(Auth::user());
	}

	/**
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function update(Request $request, Scrubber $scrubber)
	{
		$user = Auth::user();
		$this->validate($request, User::validationRules($user));

		$user->update([
			'first_name'	=> $request->get('first_name'),
			'last_name'		=> $request->get('last_name'),
			'email'			=> $request->get('email'),
			'phone'			=> $scrubber->integer($request->get('phone')),
			'gender'		=> $request->get('gender')
		]);

		return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
	}

}
