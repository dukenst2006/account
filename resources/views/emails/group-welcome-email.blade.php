@component('mail::message')
# Registration Confirmation

@if ($hasEmailBody)
{{ $emailBody }}
@endif

Please double check the below information to ensure everything is correct.  If you find a mistake, you can [login to your Bible Bowl account]({{ url('login') }}) and correct the mistake.

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

@endcomponent