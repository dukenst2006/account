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
                                    {!! Form::textarea('welcome-email', $settings->registrationEmailContents(), ['id' => 'text-editor', 'style' => 'width: 100%']) !!}
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
@endsection

@includeRichTextEditor
@includeNotifications
@jsData
var groupId = {{ $group->id }},
    email = '{{ Auth::user()->email }}';
@endjsData
@includeJs(/js/group-email-settings.js)