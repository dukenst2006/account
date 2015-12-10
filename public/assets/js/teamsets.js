var vm = new Vue({
    el: '#page',
    data: {
        // When true, user can edit the teamSet name
        isEditingName: false,

        teamSet: teamSet,
        teamSetRules: {
            required: false,
            maxlength: 64
        }
    },
    methods: {
        editTeamSetName: function () {
            alert('editing');
        },
        saveTeamSetName: function () {
            if(this.$teamSetValidation.valid) {
                this.doneEditing();
                var teamSet = this.teamSet,
                    self = this;

                $.ajax({
                    url: '/team/'+teamSet.id,
                    type: 'PATCH',
                    data: {
                        'name': teamSet.name
                    },
                    error: function(res) {

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
            Vue.nextTick(function () {
                $('#teamSetName').focus();
            });
        },
        doneEditing: function () {
            this.isEditingName = false;
        }
    }
});