<?php namespace BibleBowl\Http\Controllers;

use Illuminate\Http\Request;

class ChildrenController extends Controller
{
	public function __construct()
	{
		$this->middleware('requires.setup');
	}
//
//	/**
//	 * @param AddressOwnerOnlyRequest $request
//	 *
//	 * @return \Illuminate\View\View
//	 */
//	public function edit(AddressOwnerOnlyRequest $request, $id)
//	{
//		return view('account.address.edit')
//			->withAddress(Address::findOrFail($id));
//	}
//
//	/**
//	 * @param AddressOwnerOnlyRequest $request
//	 * @param                         $id
//	 *
//	 * @return mixed
//	 */
//	public function update(AddressOwnerOnlyRequest $request, $id)
//	{
//		$this->validate($request, Address::validationRules(), Address::validationMessages());
//
//		Address::findOrFail($id)->update($request->all());
//
//		return redirect('/account/address')->withFlashSuccess('Your changes were saved');
//	}
//
//	/**
//	 * @param AddressOwnerOnlyRequest 	$request
//	 * @param                      		$id
//	 *
//	 * @return mixed
//	 */
//	public function destroy(AddressOwnerOnlyRequest $request, $id)
//	{
//		$address = Address::find($id);
//		$address->delete();
//		return redirect('/account/address')->withFlashSuccess('Your '.$address->name.' address has been deleted');
//	}

}
