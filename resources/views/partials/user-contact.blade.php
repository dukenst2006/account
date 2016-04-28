@if(isset($adminLink) && $adminLink)
    <a href="/admin/users/{{ $user->id }}">{{ $user->full_name }}</a>
@else
    {{ $user->full_name }}
@endif
<br/>
@if(!is_null($user->phone))
    <a href='tel:+1{{ $user->phone }}'>{{ HTML::formatPhone($user->phone) }}</a><br/>
@endif
<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>