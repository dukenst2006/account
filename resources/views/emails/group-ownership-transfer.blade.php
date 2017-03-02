@component('mail::message')
# {{ $header }}

Ownership of **{{ $group->name }}** ({{ $group->program->name }}) has been transferred from **{{ $previousOwner->full_name }}** to **{{ $newOwner->full_name }}**.
@endcomponent
