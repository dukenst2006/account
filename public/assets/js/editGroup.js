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

    // toggle address fields based on preference
    var addressToggle = $('input[name="user_owned_address"]'),
        toggleAddressFields = function () {
            if (addressToggle.is(':checked')) {
                $('#myOwnAddresses').show();
                $('#addressForm').hide();
            } else {
                $('#myOwnAddresses').hide();
                $('#addressForm').show();
            }
        };
    toggleAddressFields();
    addressToggle.change(toggleAddressFields);
});