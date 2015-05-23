<?php namespace BibleBowl\Http\Controllers\Auth;

use BibleBowl\Http\Controllers\Controller;
use BibleBowl\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class ConfirmationController extends Controller {

	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Landing page for a user confirming their email addres
	 *
	 * @param string $guid
	 *
	 * @return mixed
	 */
	public function getConfirm($guid)
	{
		$user = User::where('guid', $guid)->firstOrFail();
		$user->status = User::STATUS_CONFIRMED;
		$user->save();

		return redirect()->back()->withFlashSuccess('Your email address has been confirmed, you may now login');
	}

}
