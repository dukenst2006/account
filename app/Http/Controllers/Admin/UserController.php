<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\User;
use Input;

class UserController extends Controller
{

    public function index()
    {
        $users = User::where('first_name', 'LIKE', '%'.Input::get('q').'%')
            ->orWhere('last_name', 'LIKE', '%'.Input::get('q').'%')
            ->orWhere('email', 'LIKE', '%'.Input::get('q').'%')
            ->with('primaryAddress')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->paginate(25);

        return view('/admin/users/index', [
            'users' => $users->appends(Input::only('q'))
        ]);
    }

    public function show($userId)
    {
        return view('/admin/users/show', [
            'user' => User::findOrFail($userId)
        ]);
    }

}
