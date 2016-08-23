<?php namespace BibleBowl\Http\Controllers\Groups;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\RegistrationConfirmation;
use BibleBowl\Groups\Settings;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\Groups\UserInviteRequest;
use BibleBowl\Http\Requests\MailchimpIntegrationRequest;
use BibleBowl\Invitation;
use Illuminate\Mail\Message;
use BibleBowl\User;
use Session;
use DB;
use Mail;

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
            ->withUsers($group->users);
    }

    public function getUserInvite()
    {
        return view('group.settings.user-invite')
            ->withGroup(Session::group());
    }

    public function sendUserInvite(UserInviteRequest $request)
    {
        $group = Group::findOrFail($request->route('group'));
        $user = User::where('email', $request->get('email'))->first();

        DB::beginTransaction();

        $invitation = Invitation::create([
            'type'      => Invitation::TYPE_MANAGE_GROUP,
            'email'     => is_null($user) ? $request->get('email') : null,
            'user_id'   => is_null($user) ? null : $user
        ]);

        $recipientName = null;
        if (is_null($user)) {
            $recipientEmail = $request->get('email');
        } else {
            $recipientEmail = $user->email;
            $recipientName = $user->full_name;
        }

        Mail::queue(
            'emails.group-user-invitation',
            [
                'invitation'        => $invitation,
                'header'            => 'Group Management Invitation',
                'invitationText'    => '<strong>'.Auth::user()->full_name.'</strong> has invited you to help manage the '.$group->program->abbreviation.' <strong>'.$group->name.'</strong> group.'
            ],
            function (Message $message) use ($recipientEmail, $recipientName) {
                $message->to($recipientEmail, $recipientName)
                    ->subject('Bible Bowl Group Management Invitation');
            }
        );

        DB::commit();

        return redirect('/group/'.$group->id.'/settings/users')
            ->withFlashSuccess('Invitation has been sent');
    }
}
