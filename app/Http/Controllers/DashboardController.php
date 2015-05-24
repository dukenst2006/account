<?php namespace BibleBowl\Http\Controllers;

class DashboardController extends Controller
{
	public function __construct()
	{
		$this->middleware('requires.setup');
	}

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
