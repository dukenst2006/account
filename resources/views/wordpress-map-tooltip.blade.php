<div style="font-size:120%"><strong>{{ $group->name }}</strong></div>
<div style="margin-bottom:1em;"><a href="{{ url($group->registrationReferralLink()) }}">Register with group</a></div>

{{ $group->owner->full_name }}<br/>
<a href="mailto:{{ $group->owner->email }}">{{ $group->owner->email }}</a><br/>
<a href='tel:+1{{ $group->owner->phone }}'>{{ Html::formatPhone($group->owner->phone) }}</a><br/><br/>
