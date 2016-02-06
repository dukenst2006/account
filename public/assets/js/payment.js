$(document).ready(function () {
    // handle creation of credit card token
    $('#payment-form').submit(function(event) {
        var $form = $(this);

        // Disable the submit button to prevent repeated clicks
        $form.find('button').prop('disabled', true);

        Stripe.card.createToken({
            name: $('input[name=cardHolder]').val(),
            number: $('input[data-stripe=number]').val(),
            cvc: $('input[data-stripe=cvc]').val(),
            exp_month: $('select[data-stripe=exp-month]').val(),
            exp_year: $('select[data-stripe=exp-year]').val()
        }, function (status, response) {
            var $form = $('#payment-form'),
                $errors = $form.find('.payment-errors').hide();

            if (response.error) {
                // Show the errors on the form
                $errors.text(response.error.message).show();
                $form.find('button').prop('disabled', false);
            } else {
                // response contains id and card, which contains additional card details
                var token = response.id;
                // Insert the token into the form so it gets submitted to the server
                $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                // and submit
                $form.get(0).submit();
            }
        });

        // Prevent the form from submitting with the default action
        return false;
    });
});