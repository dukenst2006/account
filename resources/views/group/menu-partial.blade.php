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
    <li
        @if($selected == 'users')
            class="active bold"
        @endif>
        <a href="/group/{{ $group->id }}/settings/users">Users</a>
    </li>
    <li
        @if($selected == 'integrations')
            class="active bold"
        @endif>
        <a href="/group/{{ $group->id }}/settings/integrations">Integrations</a>
    </li>
</ul>