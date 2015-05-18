<?php namespace BibleBowl\Http\Controllers\Account;

use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\Addresses\DestroyAddressRequest;

class AddressController extends Controller
{

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('account.address.index');
	}

	/**
	 * @param DestroyAddressRequest $request
	 * @param                      $id
	 *
	 * @return mixed
	 */
	public function destroy(DestroyAddressRequest $request, $id)
	{

	}

}
