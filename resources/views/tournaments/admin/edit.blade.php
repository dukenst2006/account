@extends('layouts.master')

@section('title', 'Editing '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    {!! Form::model($tournament, ['route' => ['admin.tournaments.update', $tournament->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Tournament</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')

                        <div class="row">
                            <div class="col-md-6 col-sm-6 form-group">
                                <label class="form-label">Name <span class="required">*</span></label>
                                <span class="help"></span>
                                <div class="controls">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 128]) !!}
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>

                        @include('tournaments.admin.form')

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="form-label">Tournament Status</label>
                                <div class="controls p-b-20 p-l-20">
                                    <div class="radio">
                                        <input id="active" type="radio" name="active" value="1" {{ (old('active', $tournament->active) == 1 ? 'CHECKED' : '') }}>
                                        <label for="active"><strong>Active</strong></label> - makes the tournament visible, though registration dates are still obeyed.<br/>
                                        <input id="inactive" type="radio" name="active" value="0" {{ (old('active', $tournament->active) == 0 ? 'CHECKED' : '') }}>
                                        <label for="inactive"><strong>Inactive</strong></label> - prevents tournament from being visible by the masses until all of the dates/events are finalized.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-cons" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection