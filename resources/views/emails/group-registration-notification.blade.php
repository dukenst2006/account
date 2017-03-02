@component('mail::message')
# Registration Notification

<?php echo (count($players) > 1 ? 'A few players have' : 'A player has'); ?> just registered for **{{ $group->name }} ({{ $group->program->name }})**.  You can see where they live in relation to where your group meets on the [Player Map]({{ url('roster/map') }}).

**{{ $guardian['full_name'] }}**<br/>
{{ $guardian->primaryAddress->address_one }}<br/>
@if(!is_null($guardian->primaryAddress->address_two))
    {{ $guardian->primaryAddress->address_two }}<br/>
@endif
{{ $guardian->primaryAddress->city }}, {{ $guardian->primaryAddress->state }} {{ $guardian->primaryAddress->zip_code }}<br/>
[{{ $guardian['email'] }}](mailto:{{ $guardian['email'] }})
@if(isset($guardian['phone']) && !empty($guardian['phone']))
    [{{ Html::formatPhone($guardian['phone']) }}](tel:{{ $guardian['phone'] }})@endif

@component('mail::table')
| Name       | Gender         | Age  | Grade  | T-Shirt Size  |
| ------------- |:-------------:|:--------:|:--------:|:--------:|
@foreach ($players as $player)
    | {{ $player['full_name'] }}      | {{ $player['gender'] }}      | {{ $player->age() }}      | {{ \App\Presentation\Describer::describeGradeShort($grades[$player['id']]) }}      | {{ $shirtSizes[$player['id']] }}      |
@endforeach

@endcomponent

*If you do not want to receive these emails, please go to [Notification Preferences]({{ url('account/notifications') }}) to disable them.*

@endcomponent