<?php
$programs = \App\Program::orderBy('name', 'ASC')->get();
?>
@component('mail::message')
# Automatically Deactivated Groups

Groups automatically become inactive when they end a season without any active players.  This time, **{{ count($groupIds) }}** met the criteria.  They have already been notified as well as provided instructions on how to reactivate their group or transfer the group ownership to another individual.  There's nothing you need to do, this is merely a notification that the following groups are now inactive.

@foreach($programs as $program)
## {{ $program->name }}

<?php $groups = \App\Group::whereIn('id', $groupIds)->where('program_id', $program->id)->with('owner')->get(); ?>
@foreach ($groups as $group)
 * {{ $group->name }} ({{ $group->owner->full_name }})
@endforeach
@endforeach

The group owners have been notified.
@endcomponent