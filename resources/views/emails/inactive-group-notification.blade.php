@component('mail::message')
# Group Automatically Deactivated

**{{ $group->name }} ({{ $group->program->name }})** has been automatically deactivated because there were no active players for your group at the end of the **{{ $season->name }} season**.

While inactive, your group will:

 * not accept new player registrations for upcoming seasons
 * be excluded from the group map on the main web site

If your group should not be inactive, please [update your group's settings]({{ url('group/'.$group->id.'/edit') }}).  If you wish to transfer ownership of this group to someone else, please forward this email to **{{ config('biblebowl.officeEmail') }}** and include the user's name and email address that you'd like to transfer the group to.  The new owner must have already created a Bible Bowl account.

Group ID: {{ $group->id }}

@endcomponent