@extends('layouts.master')

@section('title', 'Editing My Account')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">My Account</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::model($user, ['url' => ['/account/update/'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Name</label>
                                    <span class="help"></span>
                                    <div class="controls row p-b-20">
                                        <div class="col-md-6">
                                            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 32]) !!}
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 32]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Email</label>
                                    <span class="help"></span>
                                    <div class="controls p-b-20">
                                        {!! Form::text('email', null, ['class' => 'form-control', 'maxlength' => 255]) !!}<br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <span class="help"></span>
                                    <div class="controls p-b-20">
                                        {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone', 'maxlength' => 10]) !!}<br/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        @include('partials.forms.gender')
                                    </div>
                                </div>
                            </div>
                            @if(is_null($user->password) == false)
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Change Password</label>
                                    <span class="help"></span>
                                    <div class="controls row p-b-20">
                                        <div class="col-md-6">
                                            {!! Form::password('password', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::password('password_confirmation', null, ['class' => 'form-control', 'placeholder' => 'New password confirmation', 'maxlength' => 60]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
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