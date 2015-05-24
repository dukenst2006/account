@extends('layouts.master')

@section('title', 'New Address')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>New <span class="semi-bold">Address</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['url' => ['/account/address'], 'class' => 'form-horizontal', 'role' => 'form']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Name</label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name', 'maxlength' => 32, 'autofocus']) !!}<br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Contact Name</label>
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
                            @include('account.address.form')
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