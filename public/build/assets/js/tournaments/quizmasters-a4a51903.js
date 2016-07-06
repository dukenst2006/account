/**
 * NOTE: Only the API endpoints are covered by tests
 */

var vm = new Vue({
    el: '#page',
    data: {
        quizmasters: quizmasters,
    },
    methods: {
        findQuizmaster: function () {
            $.ajax({
                url: '/account/findByEmail/'+this.email,
                type: 'GET',
                error: function(r) {
                    console.log(this.quizmaster);
                    // no user exists with that email
                    Messenger().post({
                        message: 'Unable to save changes',
                        type: 'error',
                        hideAfter: 3
                    });
                },
                success: function(r) {
                    console.log(r);
                }
            });
        },
        removeQuizmaster: function (quizmaster) {
            this.quizmasters.$remove(quizmaster);
        },
        addQuizmaster: function () {
            this.quizmasters.push({
                gender: 'M'
            });
        },
        saveTeamName: function () {
            var error = null,
                team = this.teamSet.teams[this.isEditingTeamIndex];

            if (team.name.length == 0) {
                Messenger().post({
                    message: 'A name is required.',
                    type: 'error',
                    hideAfter: 3
                });
                return;
            }

            this.isEditingTeamIndex = null;
            updateTeam(team.id, team.name);
        },
    }
});
vm.addQuizmaster();