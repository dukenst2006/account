<?php namespace BibleBowl\Http\Controllers\Groups;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupCreator;
use BibleBowl\Groups\RegistrationConfirmation;
use BibleBowl\Groups\Settings;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupCreationRequest;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Program;
use Input;
use Session;

class SettingsController extends Controller
{
    public function editEmail(GroupCreatorOnlyRequest $request)
    {
        $group = Session::group();
        return view('group.settings.email')
            ->withGroup($group)
            ->with('settings', $group->settings);
    }

    public function postEmail(GroupCreatorOnlyRequest $request)
    {
        /** @var Group $group */
        $group = Group::findOrFail($request->route('group'));

        /** @var Settings $settings */
        $settings = $group->settings;

        $settings->setRegistrationEmailContents($request->get('welcome-email'));
        $group->settings = $settings;
        $group->save();

        Session::setGroup($group);

        return redirect('/group/'.$group->id.'/settings/email')
            ->withFlashSuccess('Your email settings have been saved');
    }

    public function sendTestEmail(GroupCreatorOnlyRequest $request, RegistrationConfirmation $registrationConfirmation)
    {
        /** @var Group $group */
        $group = Group::findOrFail($request->route('group'));

        $registrationConfirmation->sendTest(Auth::user(), $group, $request->get('body'));
    }

    public function editIntegrations(GroupCreatorOnlyRequest $request)
    {

    }

    public function postIntegrations(GroupCreatorOnlyRequest $request)
    {

    }
}
