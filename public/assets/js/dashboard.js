// used by the modal that contains the reg link
function copyToClipboard(el) {
    $(el).select();
    document.execCommand("copy");
}

$(document).ready(function () {
    $(document).on('click', 'input.click-copy', function (e) {
        copyToClipboard(e.target);

    });
});