<?php namespace BibleBowl\Http\Controllers\Account;

use Auth;
use BibleBowl\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Redirect;

class NotificationController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('account.notifications')
            ->withSettings(Auth::user()->settings);
    }

    /**
     * @return mixed
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = $user->settings;
        $settings->notifyWhenUserJoinsGroup($request->input('notifyWhenUserJoinsGroup') == 1);
        $user->update([
            'settings' => $settings
        ]);

        return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
    }

}
