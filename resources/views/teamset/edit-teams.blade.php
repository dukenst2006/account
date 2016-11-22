<div class="row">
    @if($teamSet->canBeEdited(Auth::user()))
    <div id="roster" class="col-md-2 col-sm-3 col-xs-6 m-t-10">
        <h5 class="text-center">Players</h5>
        <ul class="players editable">
        @foreach($players as $player)
            <li class="grade-{{ $player->seasons->first()->pivot->grade }}" data-playerId="{{ $player->id }}">
                <label>{{ $player->full_name }}</label>
            </li>
        @endforeach
        </ul>
    </div>
    <div id="teams" class="col-md-10 col-sm-9 col-xs-6 row">
    @else
    <div id="teams" class="col-md-12 row">
    @endif
        @include('teamset.team-list')
    </div>
</div>