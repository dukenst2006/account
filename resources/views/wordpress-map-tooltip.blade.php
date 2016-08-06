<strong>{{ $group->name }}</strong>
<br/><br/>
{{ $group->owner->full_name }}<br/>
<a href="mailto:{{ $group->owner->email }}">{{ $group->owner->email }}</a><br/>
<a href='tel:+1{{ $group->owner->phone }}'>{{ HTML::formatPhone($group->owner->phone) }}</a>