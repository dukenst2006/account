<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = User::findOrFail(Auth::user()->id);

        return view('account.notifications')
            ->withSettings($user->settings);
    }

    /**
     * @return mixed
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $settings = $user->settings;
        $settings->notifyWhenUserJoinsGroup($request->get('notifyWhenUserJoinsGroup') == 1);
        $user->update([
            'settings' => $settings,
        ]);

        return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
    }
}
