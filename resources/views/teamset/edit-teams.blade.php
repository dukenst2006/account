<div class="row">
    @if($teamSet->canBeEdited(Auth::user()))
    <div id="roster" class="col-md-2 col-sm-3 col-xs-5 m-t-10">
        <h5 class="text-center">Players</h5>
        <ul class="players editable">
        @foreach($players as $player)
            <li class="grade-{{ $player->seasons->first()->pivot->grade }}" data-playerId="{{ $player->id }}">
                <label>{{ $player->first_name }} <span class="hidden-xlg hidden-lg hidden-md" style="display: inline-block">{{ $player->last_name[0] }}.</span> <span class="hidden-sm hidden-xs" style="display: inline-block">{{ $player->last_name }}</span></label>
            </li>
        @endforeach
        </ul>
    </div>
    <div id="teams" class="col-md-10 col-sm-9 col-xs-7 row">
    @else
    <div id="teams" class="col-md-12 row">
    @endif
        @include('teamset.team-list')
    </div>
</div>