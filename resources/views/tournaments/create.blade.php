@extends('layouts.master')

@section('title', 'New Tournament')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>New <span class="semi-bold">Tournament</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['url' => '/tournaments', 'role' => 'form']) !!}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="form-label">Name <span class="required">*</span></label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 128]) !!}
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-5 form-group">
                                    <label class="form-label">Program</label>
                                    <span class="help"></span>
                                    <div class="controls p-b-10">
                                        {!! Form::select('program_id', $programs, null, ['class' => 'form-control']) !!}<br/>
                                    </div>
                                </div>
                            </div>
                            @include('tournaments.form')
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