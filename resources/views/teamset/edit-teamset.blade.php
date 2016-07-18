<div class="grid simple">
    <div class="grid-title no-border">
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-8">
                @include('teamset.edit-teamset-name', [
                    'teamSet' => $teamSet
                ])
            </div>
            <div class="col-md-6 col-sm-4 col-xs-4 text-right">
                <a href="/teamsets/{{ $teamSet->id }}/pdf" class="btn btn-primary m-r-10"><i class="fa fa-download"></i> PDF</a>
                <div class="btn btn-info" @click="addTeam()">+ Add Team</div>
            </div>
        </div>
        <div class="b-grey b-b"></div>
    </div>
    <div class="grid-body no-border p-t-20">
        @include('teamset.edit-teams', [
            'teams'     => $teamSet->teams,
            'players'   => $players
        ])
    </div>
</div>