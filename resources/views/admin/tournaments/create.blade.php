@extends('layouts.master')

@section('title', 'New Tournament')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>New <span class="semi-bold">Tournament</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['url' => '/admin/tournaments', 'role' => 'form']) !!}
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Name <span class="required">*</span></label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 128]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Tournament Dates <span class="required">*</span></label>
                                    <div class="controls p-b-20">
                                        <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->start->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                                            {!! Form::text('start', (isset($tournament) ? $tournament->start->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'Start']) !!}
                                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                        </div> -
                                        <div class="input-append success date m-l-50" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->end->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                                            {!! Form::text('end', (isset($tournament) ? $tournament->end->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'End']) !!}
                                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 p-b-30">
                                    <label class="form-label">Registration Dates <span class="required">*</span></label>
                                    <div class="controls p-b-20">
                                        <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->start->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                                            {!! Form::text('registration_start', (isset($tournament) ? $tournament->registration_start->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'Start']) !!}
                                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                        </div> -
                                        <div class="input-append success date m-l-50" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->end->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                                            {!! Form::text('registration_end', (isset($tournament) ? $tournament->registration_end->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'End']) !!}
                                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">More information</label>
                                    <span class="help">Provide a URL where people can obtain more information about this tournament</span>
                                    <div class="controls p-b-20">
                                        {!! Form::url('url', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
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

@includeCss(/assets/plugins/bootstrap-datepicker/css/datepicker.min.css)
@includeJs(/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js)
@js
    $(document).ready(function () {
        $('.input-append.date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    });
@endjs