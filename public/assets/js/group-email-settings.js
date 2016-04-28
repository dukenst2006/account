$('#text-editor').wysihtml5({
    stylesheets: ["/assets/plugins/bootstrap-wysihtml5/wysiwyg-color.css"],
    image: false,
    color: false,
});
var sending = false;
$('#send-test').click(function (e) {
    if (sending == false) {
        $('#send-icon').switchClass('fa-envelope-o', 'fa-refresh');
        sending = true;
        $.ajax({
            type: 'post',
            url: '/group/'+groupId+'/settings/test-email',
            data: {
                body: $('#text-editor').val()
            },
            success: function () {
                sending = false;
                $('#send-icon').switchClass('fa-refresh', 'fa-envelope-o');
                Messenger().post({
                    message: 'Email is on its way to '+email,
                    type: 'success',
                    hideAfter: 3
                });
            },
            error: function () {
                sending = false;
                $('#send-icon').switchClass('fa-refresh', 'fa-envelope-o');
                Messenger().post({
                    message: 'Unable to send test email, please refresh page and try again',
                    type: 'error',
                    hideAfter: 3
                });
            }
        });
    }
});