<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Support\Scrubber;
use App\User;
use Auth;
use Illuminate\Http\Request;

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
            'first_name'       => $request->get('first_name'),
            'last_name'        => $request->get('last_name'),
            'email'            => $request->get('email'),
            'phone'            => $scrubber->integer($request->get('phone')),
            'gender'           => $request->get('gender'),
        ]);

        // update user timezone
        $user = Auth::user();
        $settings = $user->settings;
        $settings->setTimezone($request->input('timezone'));
        $user->update([
            'settings' => $settings,
        ]);

        event('user.profile.updated', $user);

        return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
    }
}
