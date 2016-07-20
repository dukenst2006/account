@extends('layouts.master')

@section('title', 'Editing '.$address->name.' Address')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Address</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::model($address, ['url' => ['/account/address/'.$address->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Name</label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name', 'maxlength' => 32, 'autofocus']) !!}<br/>
                                    </div>
                                </div>
                            </div>
                            @include('account.address.form')
                            <div class="row">
                                <div class="col-md-12 text-center m-t-20">
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