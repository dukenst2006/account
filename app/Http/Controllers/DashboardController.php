<?php namespace BibleBowl\Http\Controllers;

class DashboardController extends Controller
{

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('dashboard');
	}

}
