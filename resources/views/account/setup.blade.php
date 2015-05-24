@extends('layouts.frontend_master')

@section('title', 'Account Registration')

@section('after-styles-end')
    <link href="{!! elixir('css/forms.css') !!}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
    <script src="/assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="{!! elixir('js/forms.js') !!}" type="text/javascript"></script>
@endsection

@section('content')
    <br/>
    <br/>
    <div class="row">
        <div class="grid simple">
            <div class="col-md-8 col-md-offset-2 grid-body no-border">
                <br/>
                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title">
                            <h3>Account <span class="semi-bold">Information</span></h3>
                            <p>You're almost there!</p>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                <div class="row">
                    <div class="col-md-12"> <br>
                        @include('partials.messages')
                        {!! Form::open(['method' => 'post']) !!}
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label">Name<span class="text-error">*</span></label>
                                <span class="help"></span>
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 64, 'autofocus']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 64]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Phone<span class="text-error">*</span></label>
                                <span class="help"></span>
                                <div class="controls p-b-20">
                                    {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone', 'maxlength' => 10]) !!}<br/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender<span class="text-error">*</span></label>
                                <span class="help"></span>
                                <div class="controls">
                                    @include('partials.forms.gender')
                                </div>
                            </div>
                        </div>
                        <h4>Home <span class="semi-bold">Address</span></h4>
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