@extends('emails.simple')

@section('body')

    @include('emails.theme.header', [
        'header' => 'Group Automatically Deactivated'
    ])

    @include('emails.theme.text-block', [
        'body' => '<p><strong>'.$group->name.' ('.$group->program->name.')</strong> has been automatically deactivated because there were no active players for your group at the end of the <strong>'.$season->name.' season</strong>.</p>
        <p>
            While inactive, your group will:
            <ul>
                <li>not accept new player registrations for upcoming seasons</li>
                <li>be excluded from the group map on the main web site</li>
            </ul>
            If your group should not be inactive, please '.EmailTemplate::link(url('group/'.$group->id.'/edit'), "update your group's settings").'.  If you wish to transfer ownership of this group to someone else, please forward this email to <strong>'.config('biblebowl.officeEmail').'</strong> and include the user\'s name and email address that you\'d like to transfer the group to.  The new owner must have already created a Bible Bowl account.
        </p>'
    ])

    @include('emails.theme.text-block', [
        'body' => '<p style="font-style: italic; padding-top: 10px; font-size: 80%">Group ID: '.$group->id.'</p>'
    ])
@endsection