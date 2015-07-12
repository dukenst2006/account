// used by the modal that contains the reg link
function copyToClipboard(el) {
    $(el).select();
    document.execCommand("copy");
}

$(document).ready(function () {

    /**
     * Click Copy Event.
     */
    $('input.click-copy, .btn-copy').on('click', function(e) {

        // Trigger the btn-copy if the input is clicked.
        if ($(e).hasClass('click-copy')) {
            $('.btn-copy').trigger('click');
        }
        else {
            // Copy the registration link into the users clipboard.
            copyToClipboard($('input.click-copy'));

            // Change the "Copy" icon into a "Checkmark" to indicate success.
            $icon = $('.btn-copy i');
            $icon.fadeOut(50, function(){
                $(this).removeClass('fa-paste')
                    .addClass('fa-check')
                    .fadeIn(200)
                    .animate({top: 0}, 200);
                $(this).siblings('span')
                    .text('Copied!');
            });
        }
    });
});