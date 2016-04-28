@extends('emails.simple')

@section('body')
    <?php
        // Serialized objects need to be re-instantiated in order
        // to have a successful database connection
        $programs = \BibleBowl\Program::orderBy('name', 'ASC')->get();
    ?>

    @include('emails.theme.header', [
        'header' => 'Automatically Deactivated Groups'
    ])

    @include('emails.theme.text-block', [
        'body' => '<p>Groups automatically become inactive when they end a season without any active players.  This time, <strong>'.count($groupIds).'</strong> met the criteria.  They have already been notified as well as provided instructions on how to reactivate their group or transfer the group ownership to another individual.  There\'s nothing you need to do, this is merely a notification that the following groups are now inactive.</p>'
    ])

    @foreach($programs as $program)
        <?php $groups = \BibleBowl\Group::whereIn('id', $groupIds)->where('program_id', $program->id)->with('owner')->get(); ?>
        @if($groups->count() > 0)
            <?php
            $bulletedList = '';
            foreach ($groups as $group) {
                $bulletedList .= '<li>'.$group->name.' ('.$group->owner->full_name.')</li>';
            }
            ?>
            @include('emails.theme.text-block', [
                'body' => "<h4>".$program->name."</h4><ul>".$bulletedList.'</ul>'
            ])
        @endif
    @endforeach
@endsection