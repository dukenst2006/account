<ul>
    <li
        @if($selected == 'Overview')
            class="active bold"
        @endif>
        <a href="/admin/tournaments/{{ $tournament->id }}">Overview</a>
    </li>
    <li
        @if($selected == 'Coordinators')
            class="active bold"
        @endif>
        <a href="/admin/tournaments/{{ $tournament->id }}/coordinators">Coordinators</a>
    </li>
</ul>