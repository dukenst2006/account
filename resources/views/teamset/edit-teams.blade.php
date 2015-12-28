<div class="row">
    <div id="roster" class="col-md-2 m-t-10">
        <h5 class="text-center">Players</h5>
        <ul class="players">
        @foreach($players as $player)
            <li class="grade-{{ $player->seasons->first()->pivot->grade }}" data-playerId="{{ $player->id }}">
                <label>{{ $player->full_name }}</label>
            </li>
        @endforeach
        </ul>
    </div>
    <div id="teams" class="col-md-10 row">
        @include('teamset.team-list')
    </div>
</div>