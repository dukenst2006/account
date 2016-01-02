@extends('layouts.master')

@section('title', 'New Teams')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>New <span class="semi-bold">Teams</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['url' => ['/teamsets'], 'role' => 'form']) !!}
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Name</label>
                                    <span class="help"></span>
                                    <div class="controls p-b-10">
                                        {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 64]) !!}<br/>
                                    </div>
                                </div>
                            </div>
                            @if(count($teamSetOptions) > 1)
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Create From Teams</label>
                                    <span class="help">Rather than starting from scratch, you can start with a copy of some of your existing teams</span>
                                    <div class="controls p-b-10">
                                        {!! Form::select('teamSet', $teamSetOptions, null, ['class' => 'form-control']) !!}<br/>
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