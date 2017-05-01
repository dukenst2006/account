@extends('layouts.master')

@section('title', 'Group Email Settings')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $group->name }}</h3>
                    </div>
                    <div class="bar">
                        <div class="bar-inner">
                            @include('group.menu-partial', [
                                'selected' => 'email'
                            ])
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20"></div>
                    <div class="grid-body no-border p-t-20">
                        @include('partials.messages')
                        <h4 class="semi-bold">Welcome Email</h4>
                        <p>When parents register their players with your group, they'll receive an email confirmation.  Use the below field to add additional information (such as important dates, how to contact you, etc.) to that welcome email.  Don't forget to <strong>send yourself a test email</strong> before saving.</p>
                        {!! Form::open(['role' => 'form', 'method' => 'POST']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::textarea('welcome-email', old('welcome-email', $settings->registrationEmailContents()), ['id' => 'text-editor', 'style' => 'width: 100%']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center p-t-40">
                                    <button class="btn btn-primary btn-cons" type="submit">Save</button>
                                    <div class="btn btn-cons m-l-20" id="send-test"><i class="fa fa-envelope-o" id="send-icon"></i>&nbsp; Send Test E-mail</div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="justCreatedModal" tabindex="-1" role="dialog" aria-labelledby="justCreatedModal" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br>
                    <i class="fa fa-check fa-7x text-success"></i>
                    <h4 class="semi-bold">Your group has been created</h4>
                </div>
                <div class="modal-body">
                    <p>Now that you've created your group, here's some good next steps:</p>
                    <ul>
                        <li>Close this window and configure a custom welcome email for your group.</li>
                        <li>Go to your Dashboard to get a registration link you can distribute to the parents of your players.  We'll walk them through the steps to register their players with your group.</li>
                    </ul>
                    <p>There's some great benefits to having created your group, here's some highlights:</p>
                    <ul>
                        <li>Your group will be listed on BibleBowl.org so it's easier for potential players in your area can find you</li>
                        <li>Manage your player roster/teams online</li>
                        <li>Collect your player's registration fees and pay them online (${{ $group->program->registration_fee }}/player)</li>
                        <li>And more!</li>
                    </ul>
                    <div class="text-center">
                        {!! Form::button('Close', ['class' => 'btn btn-primary', 'data-dismiss' => 'modal']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@js
    @if(isset($justCreated) && $justCreated)
        $(window).load(function(){
            $('#justCreatedModal').modal('show');
        });
    @endif
@endjs

@includeRichTextEditor
@includeNotifications
@jsData
var groupId = {{ $group->id }},
    email = '{{ Auth::user()->email }}';
@endjsData
@includeJs(/js/group-email-settings.js)