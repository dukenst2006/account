<ul>
    <li
        @if($selected == 'Overview')
            class="active bold"
        @endif>
        <a href="/admin/tournaments/{{ $tournament->id }}">Overview</a>
    </li>

    <li class="
        @if($selected == 'Registrations')
            active bold
        @endif
    classic">
        <a href="javascript:;">
            Registrations <span class="arrow"></span>
        </a>
        <ul class="classic">
            <li>
                <a href="/admin/tournaments/{{ $tournament->id }}/registrations/groups">Groups</a>
            </li>
            <li>
                <a href="/admin/tournaments/{{ $tournament->id }}/registrations/spectators">Spectators</a>
            </li>
            <li>
                <a href="/admin/tournaments/{{ $tournament->id }}/registrations/quizmasters">Quizmasters</a>
            </li>
        </ul>
    </li>
    <li
        @if($selected == 'Coordinators')
            class="active bold"
        @endif>
        <a href="/admin/tournaments/{{ $tournament->id }}/coordinators">Coordinators</a>
    </li>
</ul>