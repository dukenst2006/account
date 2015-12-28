/**
 * NOTE: Only the API endpoints are covered by tests
 */

var vm = new Vue({
    el: '#page',
    data: {
        // When true, user can edit the teamSet name
        isEditingName: false,
        newName: null,
        teamSet: teamSet,
        teamSetRules: {
            required: false,
            maxlength: 64
        }
    },
    methods: {
        saveTeamSetName: function () {
            if(this.$teamSetValidation.valid) {
                this.doneEditing();
                this.teamSet.name = this.newName;
                var self = this;

                $.ajax({
                    url: '/teamsets/'+self.teamSet.id,
                    type: 'PATCH',
                    data: {
                        'name': self.newName
                    },
                    error: function(r) {
                        Messenger().post({
                            message: 'Unable to save changes',
                            type: 'error',
                            hideAfter: 3
                        });
                        self.editing();
                    }
                });
            }
        },
        editing: function () {
            this.isEditingName = true;

            if (this.newName === null) {
                this.newName = this.teamSet.name;
            }

            Vue.nextTick(function () {
                $('#teamSetName').focus();
            });
        },
        doneEditing: function () {
            this.isEditingName = false;
        },
        addTeam: function () {
            // Add the team before waiting on the ajax response
            // so the user isn't left waiting.  We'll then
            // overwrite the team that we created with the team
            // provided by the ajax response
            var teamIndex = this.teamSet.teams.length,
                name = 'Team ' + (teamIndex+1);
            this.teamSet.teams.$set(teamIndex, {
                name: name,
                players: []
            });
            createTeam(name, function (team, callback) {
                this.teamSet.teams.$set(teamIndex, team);

                setTimeout(callback, 300);
            });
        },
        deleteTeam: function (teamIndex) {
            // give the modal time to disappear before removing
            setTimeout(function () {
                var teamContainer = $('#team-'+this.teamSet.teams[teamIndex].id);

                //copy players back to the roster so they can be added to other teams
                $('#roster .players').append($('.players > li', teamContainer).clone(true));

                $.ajax({
                    url: '/teams/'+this.teamSet.teams[teamIndex].id,
                    type: 'DELETE',
                    error: function(r) {
                        Messenger().post({
                            message: 'Unable to delete team, please refresh the page',
                            type: 'error',
                            hideAfter: 3
                        });
                    }
                });

                this.teamSet.teams.splice(teamIndex, 1);
            }, 500);
        }
    }
});

 var initSortable = function () {
         $('ul.players').sortable({
             containment: "#page",
             connectWith: "ul.players",
             placeholder: "beingDragged",
             tolerance: 'pointer',
             items: 'li',
             revert: 100,
             opacity: .7,
             receive: function (e, ui) {
                 var teamId = parseInt($(ui.item[0]).closest('.team').attr('data-teamId')),
                 playerId = parseInt($(ui.item[0]).attr('data-playerId'));

                 // if an item is dragged away from a team, remove the player
                 if (ui.hasOwnProperty('sender') && ui.sender != null) {
                     var senderTeamId = parseInt(ui.sender.closest('.team').data('teamid'));

                     if ($.isNumeric(senderTeamId)) {
                         removePlayerFromTeam(senderTeamId, playerId);
                     }
                 }

                 console.log($(ui.item[0]).closest('.team').attr('data-teamId'));
                 //if no teams exist, create one when the first player is dragged
                 if (!$.isNumeric(teamId)) {
                     createTeam('Team 1', function (teamId) {
                         $(ui.item[0]).closest('.team').attr('data-teamid', teamId);

                         // Even though this is called later, it needs to be
                         // here since the teamId in the dom needs to be updated
                         // before it's called
                         addPlayerToTeam(teamId, playerId);
                     });
                 } else {
                     addPlayerToTeam(teamId, playerId);
                 }
             }
         }).disableSelection();
    },
     createTeam = function (teamName, callback) {
         $.post('/teamsets/'+teamSet.id+'/createTeam/', {
                 name: teamName
             },
             function (data) {
                 // reset sortable initialization so the new team
                 // is added to the drag path
                 callback(data, function () {
                     initSortable();
                 });
             }
         );
     },
     deleteTeam = function (teamName, callback) {
         $.ajax({
             type: 'delete',
             url: '/teamsets/' + this.teamSet.id + '/createTeam/',
             data: {
                 name: teamName
             },
             success: function (data) {
                 callback(data, function () {
                     initSortable();
                 });
             }
         });
     },
     addPlayerToTeam = function(teamId, playerId) {
         $.post('/teams/'+teamId+'/addPlayer/', {
             playerId: playerId
         });
     },
     removePlayerFromTeam = function(teamId, playerId) {
         $.post('/teams/'+teamId+'/removePlayer/', {
             playerId: playerId
         });
     };
initSortable();