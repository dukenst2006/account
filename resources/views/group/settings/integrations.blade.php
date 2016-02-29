@extends('layouts.master')

@section('title', 'Group Integrations')

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
                                'selected' => 'integrations'
                            ])
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20"></div>
                    <div class="grid-body no-border p-t-20">
                        @include('partials.messages')
                        <h4 class="semi-bold">Mailchimp</h4>
                        <p>If enabled, this integration will automatically add parents/guardians to your mailing list as they register.</p>
                        {!! Form::open(['role' => 'form', 'method' => 'POST']) !!}
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div class="checkbox check-primary">
                                        {!! Form::checkbox('mailchimp-enabled', 1, old('mailchimp-enabled', $settings->mailchimpEnabled()), ['id' => 'mailchimp-enabled']) !!}
                                        <label for="mailchimp-enabled">Enabled</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label class="form-label">API Key</label>
                                    <span class="help">We'll verify this key is valid when you save</span>
                                    <div class="controls">
                                        {!! Form::text('mailchimp-key', old('mailchimp-key', $settings->mailchimpKey()), ['class' => 'form-control', 'maxlength' => 255]) !!}<br/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mailing List ID</label>
                                    <span class="help">The ID of the list to add parents/guardians to</span>
                                    <div class="controls p-b-20">
                                        {!! Form::text('mailchimp-list-id', old('mailchimp-list-id', $settings->mailchimpListId()), ['class' => 'form-control', 'maxlength' => 24]) !!}<br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary btn-cons" type="submit">Save</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection