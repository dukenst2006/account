@extends('emails.simple')

@section('body')
    <?php
    // Serialized objects need to be re-instantiated in order
    // to have a successful database connection
    $programs = \BibleBowl\Program::orderBy('name', 'ASC')->get();
    ?>

    @include('emails.theme.header', [
        'header' => $nextSeasonName.' Season'
    ])

    @include('emails.theme.text-block', [
        'body' => '<p>The next season is scheduled to begin on <strong>'.$willRotateOn.'</strong>.  You can change this rotation date in the '. EmailTemplate::link(url('admin/settings'), 'admin settings') .'.</p>'
    ])

    <?php
    $bulletedList = '';
    foreach ($programs as $program) {
        $bulletedList .= '<li>'.$program->name.': $'.$program->registration_fee.'</li>';
    }
    ?>
    @include('emails.theme.text-block', [
        'body' => "<p>Here's a quick reminder of what the registration fees are currently set to.  If these need to change for the upcoming season, don't forget to change them before the season rotates.</p><ul>".$bulletedList.'</ul>'
    ])

@endsection