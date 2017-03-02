@component('mail::message')
# Confirm Your Email Address

To verify your email address, please follow [this link]({!! url('register/confirm/'.$user->guid) !!}).
@endcomponent