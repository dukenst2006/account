@include('emails.theme.text-block', [
    'body' => '<p style="font-style: italic; padding-top: 10px">If you do not want to receive these emails, please go to '. EmailTemplate::link(url('account/notifications'), 'Notification Preferences') .' to disable them.</p>'
])