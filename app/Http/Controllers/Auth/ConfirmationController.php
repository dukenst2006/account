<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

class ConfirmationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Landing page for a user confirming their email addres.
     *
     * @param string $guid
     *
     * @return mixed
     */
    public function getConfirm($guid)
    {
        User::where('guid', $guid)->update([
            'status' => User::STATUS_CONFIRMED,
        ]);

        return redirect()->back()->withFlashSuccess('Your email address has been confirmed, you may now login');
    }
}
