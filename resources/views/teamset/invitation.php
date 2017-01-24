<div class="text-center">
    <button class="btn btn-white btn-mini" data-toggle="modal" data-target="#invitePlayersModal">Invite Players</button>
</div>

<div class="modal fade" id="invitePlayersModal" tabindex="-1" role="dialog" aria-labelledby="invitePlayersModal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <br>
                <i class="fa fa-envelope fa-7x"></i>
                <h4 class="semi-bold">Invite Player</h4>
                <p>In rare cases a group may invite players from another group to join one of their teams.  This is intended for times where a group is not participating in a tournament but an individual player still wishes to.  All requests are subject to the tournament's eligibility rules and thus requires approval by the tournament coordinator before the player will be added to your roster.  Complete the below request and we'll send this request to the coordinator for you.</p>
                <p class="no-margin">You are still required to communciate with the player's parents directly.</p>
            </div>
            <?=Form::open(['url' => ['/teamsets/'.$teamSet->id.'/invite'], 'class' => 'form-horizontal', 'role' => 'form'])?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Player Name</label>
                        <span class="help"></span>
                        <div class="controls p-b-20">
                            <?=Form::text('player', null, ['class' => 'form-control', 'maxlength' => 64])?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Group the player regularly plays with</label>
                        <div class="controls p-b-20">
                            <?=Form::selectGroup($tournament->program_id, 'group', null, ['style'=>'width: 100%'])?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">The team you wish the player to join</label>
                        <div class="controls p-b-20">
                            <select name="team" style="width: 100%">
                                <option v-for="team in teamSet.teams" v-bind:value="team.id">{{ team.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Request Approval</button>
            </div>
            <?=Form::close()?>
        </div>
    </div>
</div>