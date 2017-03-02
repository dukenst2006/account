<?php

namespace App\Http\Controllers\Groups;

use App\Group;
use App\Groups\GroupRegistrationTest;
use App\Groups\InviteHeadCoach;
use App\Groups\RegistrationConfirmation;
use App\Groups\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupCreatorOnlyRequest;
use App\Http\Requests\Groups\RemoveUserRequest;
use App\Http\Requests\Groups\RetractUserInviteRequest;
use App\Http\Requests\Groups\UserInviteRequest;
use App\Http\Requests\MailchimpIntegrationRequest;
use App\Invitation;
use App\User;
use Auth;
use DB;
use Mail;
use Session;

class SettingsController extends Controller
{
    public function editEmail(GroupCreatorOnlyRequest $request)
    {
        $group = Session::group();

        return view('group.settings.email')
            ->withGroup($group)
            ->withSettings($group->settings)
            ->with('justCreated', $request->has('justCreated'));
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

    public function sendTestEmail(GroupCreatorOnlyRequest $request)
    {
        /** @var Group $group */
        $group = Group::findOrFail($request->route('group'));

        /** @var GroupRegistrationTest $registration */
        $registration = app(GroupRegistrationTest::class);
        $registration->addGroup($group);

        Mail::to(Auth::user())->send(new RegistrationConfirmation($request->user(), $group, $registration, $request->get('body')));
    }

    public function editIntegrations(GroupCreatorOnlyRequest $request)
    {
        $group = Session::group();

        return view('group.settings.integrations')
            ->withGroup($group)
            ->with('settings', $group->settings);
    }

    public function postIntegrations(MailchimpIntegrationRequest $request)
    {
        /** @var Group $group */
        $group = Group::findOrFail($request->route('group'));

        /** @var Settings $settings */
        $settings = $group->settings;

        $settings->setMailchimpEnabled($request->get('mailchimp-enabled'));
        $settings->setMailchimpKey($request->get('mailchimp-key'));
        $settings->setMailchimpListId($request->get('mailchimp-list-id'));
        $group->settings = $settings;
        $group->save();

        Session::setGroup($group);

        return redirect('/group/'.$group->id.'/settings/integrations')
            ->withFlashSuccess('Your integration settings have been saved');
    }

    public function listUsers()
    {
        $group = Session::group();

        return view('group.settings.users')
            ->withGroup($group)
            ->withUsers($group->users)
            ->with('pendingInvitations', $group->invitations()->with('user')->where('status', Invitation::SENT)->get());
    }

    public function getUserInvite()
    {
        return view('group.settings.user-invite')
            ->withGroup(Session::group());
    }

    public function removeUser(RemoveUserRequest $request, $userId)
    {
        $group = Session::group();

        DB::beginTransaction();
        $group->removeHeadCoach(User::findOrFail($userId));
        DB::commit();

        return redirect()->back()->withFlashSuccess('User has been removed');
    }

    public function sendUserInvite(UserInviteRequest $request)
    {
        $group = Group::findOrFail($request->route('group'));
        $user = User::where('email', $request->get('email'))->first();

        DB::beginTransaction();

        $recipientName = null;
        if (is_null($user)) {
            $recipientEmail = $request->get('email');
        } else {
            $recipientEmail = $user->email;
            $recipientName = $user->full_name;
        }

        $invitation = Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_GROUP,
            'email'         => is_null($user) ? $request->get('email') : null,
            'user_id'       => is_null($user) ? null : $user->id,
            'inviter_id'    => Auth::user()->id,
            'group_id'      => $group->id,
        ]);

        Mail::to($recipientEmail, $recipientName)->queue(new InviteHeadCoach($invitation));

        DB::commit();

        return redirect('/group/'.$group->id.'/settings/users')
            ->withFlashSuccess('Invitation has been sent');
    }

    public function retractInvite(RetractUserInviteRequest $request, $groupId, $invitationId)
    {
        Invitation::where('id', $invitationId)->delete();

        return redirect('/group/'.$request->route('group').'/settings/users')
            ->withFlashSuccess('Invitation has been retracted');
    }
}
