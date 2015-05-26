<?php namespace BibleBowl\Http\Controllers\Account;

use Auth;
use BibleBowl\Http\Requests\AddressOwnerOnlyRequest;
use Illuminate\Http\Request;
use Redirect;
use BibleBowl\Address;
use BibleBowl\Http\Controllers\Controller;

class AddressController extends Controller
{

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('account.address.index');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('account.address.create');
	}

	/**
	 * @return mixed
	 */
	public function store(Request $request)
	{
		$this->validate($request, Address::validationRules(), Address::validationMessages());

		$request->merge([
			'user_id' => Auth::id()
		]);

		$address = Address::create($request->all());

		return redirect('/account/address')->withFlashSuccess('Your '.$address->name.' address has been created');
	}

	/**
	 * @param AddressOwnerOnlyRequest $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function edit(AddressOwnerOnlyRequest $request, $id)
	{
		return view('account.address.edit')
				->withAddress(Address::findOrFail($id));
	}

	/**
	 * @param AddressOwnerOnlyRequest $request
	 * @param                         $id
	 *
	 * @return mixed
	 */
	public function update(AddressOwnerOnlyRequest $request, $id)
	{
		$this->validate($request, Address::validationRules(), Address::validationMessages());

		Address::findOrFail($id)->update($request->all());

		return redirect('/account/address')->withFlashSuccess('Your changes were saved');
	}

	/**
	 * @param AddressOwnerOnlyRequest 	$request
	 * @param                      		$id
	 *
	 * @return mixed
	 */
	public function destroy(AddressOwnerOnlyRequest $request, $id)
	{
		$address = Address::find($id);
		$address->delete();
		return redirect('/account/address')->withFlashSuccess('Your '.$address->name.' address has been deleted');
	}

}