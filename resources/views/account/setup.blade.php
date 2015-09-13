@extends('layouts.frontend_master')

@section('title', 'Account Registration')

@section('scripts')
    <script src="/assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="{!! elixir('js/forms.js') !!}" type="text/javascript"></script>
@endsection

@section('content')
    @include('partials.logo-header')
    <div class="p-t-40">
        <div class="grid simple">
            <div class="col-md-8 col-md-offset-2 grid-body no-border">
                <br/>
                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title">
                            <h3>Account <span class="semi-bold">Information</span></h3>
                            <p>Keep it up, you're almost there!</p>
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
                                <label class="form-label">Name</label>
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
                        <h4>Home <span class="semi-bold">Address</span></h4>
                        @include('account.address.form')
                        <div class="row">
                            <div class="col-md-12 text-center p-t-20">
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