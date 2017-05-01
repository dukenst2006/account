@extends('layouts.frontend_master')

@section('title', 'Account Registration')

@section('content')
    @include('partials.logo-header')
    <div class="p-t-40">
        <div class="grid simple">
            <div class="col-md-8 col-md-offset-2 grid-body no-border">
                <h1 class="text-center m-t-20 m-b-20">Frequently Asked Questions</h1>

                <h4>Can a group have more than one Head Coach?</h4>
                <p>There isn't currently a way to do this, but ff this is a need you have, <a href="http://home.biblebowl.org/contact-us/" target="_blank">we'd like to hear from you</a> on how having multiple Head Coaches can help make things easier for you.</p>

                <h4 class="m-t-30">What if my group charges additional registration fees?</h4>
                <p>The registration confirmation email that parents receive immediately after registering with your group is a great place for this kind of information.</p>
                <p>To customize this email click on your group's name in the top right corner and go to "Settings".</p>
            </div>
        </div>
    </div>
@endsection