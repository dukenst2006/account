<ul>
    <li
        @if($selected == 'profile')
            class="active bold"
        @endif>
        <a href="/group/{{ $group->id }}/edit">Profile</a>
    </li>
    <li
        @if($selected == 'email')
            class="active bold"
        @endif>
        <a href="/group/{{ $group->id }}/settings/email">E-mail Settings</a>
    </li>
</ul>