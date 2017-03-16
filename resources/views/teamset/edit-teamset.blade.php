<div class="grid simple">
    <div class="grid-title no-border">
        <div class="alert alert-notice visible-xs">
            This page is not optimized for mobile devices and may not work as intended.
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-12">
                @include('teamset.edit-teamset-name', [
                    'teamSet' => $teamSet
                ])
            </div>
            <div class="col-md-6 col-sm-4 col-xs-12 text-right">
                <a href="/teamsets/{{ $teamSet->id }}/pdf" class="btn btn-info m-r-10" target="_blank"><i class="fa fa-download"></i> PDF</a>
                @if($teamSet->canBeEdited(Auth::user()))
                    <div id='add-team' class="btn btn-primary" @click="addTeam()">+ Add Team</div>
                @endif
            </div>
        </div>
        <div class="b-grey b-b"></div>
    </div>
    <div class="grid-body no-border p-t-20">
        @if($teamSet->tournament_id != null && $teamSet->tournament->shouldWarnAboutTeamLocking())
            <div class="alert alert-info m-t-10 text-center">
                You have {{ $teamSet->tournament->lock_teams->diffForHumans(null, true) }} left to make changes to these teams before they're locked
            </div>
        @endif
        @if($teamSet->registeredWithTournament() && $teamSet->tournament->teamsAreLocked())
            <div class="alert alert-info m-t-10 text-center">
                Teams are locked and can no longer be modified
            </div>
        @endif

        @include('teamset.edit-teams', [
            'teamSet'   => $teamSet,
            'teams'     => $teamSet->teams,
            'players'   => $players
        ])
    </div>
</div>