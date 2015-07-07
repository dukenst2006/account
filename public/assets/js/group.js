$(document).ready(function () {
    // add suffix to name based on group type
    var type = $('select[name="type"]'),
        suffixToggle = function () {
            if (type.val() == 1) {
                var name = 'Beginner Bowl';
            } else {
                var name = 'Bible Bowl';
            }
            $('#name-suffix > span').html(name);
        };
    suffixToggle();
    type.change(suffixToggle);
});