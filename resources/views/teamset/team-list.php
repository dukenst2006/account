<div v-show="teamSet.teams.length == 0" class="text-center p-t-40" id="no-teams">
    To get started you'll need to add your first team by clicking "+ Add Team"
</div>
<div style="display: flex;flex-flow: row wrap;justify-content: flex-start;">
<div v-for="team in teamSet.teams" class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    <div id="team-{{ team.id }}" class="team col-md-12" data-teamId="{{ team.id }}">
        <div class="edit fa fa-edit" v-if="canEditTeamName" @click="editingTeamName($index, $event)" @blur="doneEditingTeamName()"></div>
        <div class="delete fa fa-trash-o" v-if="canEditTeams" data-toggle="modal" data-target="#teamDeleteConfirmation-{{ team.id }}" data-team-name="{{ team.name }}"></div>
        <input v-if="isEditingTeamIndex == $index" type="text" v-model="team.name" class="bold m-l-15 edit-team-name" @keyup.enter="saveTeamName()" @keyup.esc="isEditingTeamIndex = null" @blur="isEditingTeamIndex = null" maxlength="16"/>
        <h5 v-if="isEditingTeamIndex != $index">
            <span class="name">{{ team.name }}</span>
            <i class="fa fa-warning text-error team-requirements-error" data-toggle="tooltip" title="This team must have between {{ minPlayersPerTeam }}-{{ maxPlayersPerTeam }} players."></i>
        </h5>
        <div class="drag-here" v-cloak v-show="canEditTeams && team.players.length == 0 && $index == 0">
            Drag players here
        </div>
        <ul class="players" v-bind:class="{'editable': canEditTeams}">
            <li v-for="player in team.players" class="grade-{{ player.seasons[0].pivot.grade }}" data-playerId="{{ player.seasons[0].pivot.player_id }}">
                <label>{{ player.full_name }}</label>
            </li>
        </ul>
    </div>
    <div class="modal fade" id="teamDeleteConfirmation-{{ team.id }}" tabindex="-1" role="dialog" aria-labelledby="teamDeleteConfirmation-{{ team.id }}" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <h4 class="semi-bold">Are you sure you want to delete "{{ team.name }}"</h4>
                </div>
                <div class="modal-body">
                    <button class="btn btn-block btn-danger" type="button" data-dismiss="modal" @click="deleteTeam($index)">
                        <i class="fa fa-trash-o"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>