<?php
$teamCount = $teamSet->teams()->count();
$playerCount = $teamSet->players()->count();
?>
<div class="row b-b b-grey p-b-10">
    <div class="col-md-3 p-t-20">
        Teams:
    </div>
    <div class="col-md-9 p-t-10">
        <a href="/teamsets/{{ $teamSet->id }}/pdf" target="_blank"><strong>{{ $teamSet->name }}</strong></a> (<a href="/tournaments/{{ $tournament->slug }}/group/choose-teams">change</a>)
        <div class="help">{{ $playerCount }} players on {{ $teamCount }} team<?=($teamCount > 1 ? 's':'')?></div>
    </div>
</div>