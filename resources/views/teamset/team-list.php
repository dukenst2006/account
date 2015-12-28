<div v-for="team in teamSet.teams" class="col-sm-2 col-md-4 col-lg-4">
    <div id="team-{{ team.id }}" class="team col-md-12" data-teamId="{{ team.id }}">
        <div class="delete fa fa-trash-o" data-toggle="modal" data-target="#teamDeleteConfirmation-{{ team.id }}" data-team-name="{{ team.name }}"></div>
        <h5>
            {{ team.name }}
        </h5>
        <ul class="players">
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