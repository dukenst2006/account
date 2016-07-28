<div class="alert alert-info text-center">Online registration is now closed.
    @if($tournament->allowsOnSiteRegistration())
        Additional @describe($tournament->participantTypesWithOnSiteRegistration(), and, summary) registrations will be accepted onsite.
    @endif
</div>