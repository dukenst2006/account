@extends('emails.simple')

@section('body')
    <?php
        // Serialized objects need to be re-instantiated in order
        // to have a successful database connection
        $group = \BibleBowl\Group::findOrFail($groupId);
        $season = \BibleBowl\Season::current()->first();
        $players = $group->players()->pendingRegistrationPayment($season)->get();

        $bulletedList = '';
        foreach ($players as $player) {
            $bulletedList .= '<li>'.$player->full_name.'</li>';
        }
    ?>

    @include('emails.theme.header', [
        'header' => 'Registration Fee Reminder'
    ])

    @include('emails.theme.text-block', [
        'body' => '<p><strong>'.$group->name.'</strong> has <strong>'.count($players).'</strong> player(s) with outstanding '.$group->program->name.' registration fees.  Please '.EmailTemplate::link(url('/'), 'login to your Bible Bowl account').' and click "Pay Now" to pay their fees.</p>'
    ])

    @include('emails.theme.text-block', [
        'body' => '<p>Players are more than welcome to try out Bible Bowl for a brief period.  If they try it out and decide not to play, please login and mark them as "Inactive" in your '.EmailTemplate::link(url('/roster'), 'player roster').' to avoid future emails.</p>'
    ])

    @include('emails.theme.text-block', [
        'body' => "<p>Here's a list of players with outstanding fees:</p><ul>".$bulletedList.'</ul>'
    ])
@endsection