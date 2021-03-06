@extends('layouts.master')

@section('title', 'New Group')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>New <span class="semi-bold">Group</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['url' => ['/group'], 'role' => 'form']) !!}
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form-label">Program</label>
                                    <span class="help"></span>
                                    <div class="controls p-b-10">
                                        {!! Form::select('program_id', $programs, null, ['class' => 'form-control']) !!}<br/>
                                    </div>
                                </div>
                            </div>
                            @include('group.form')
                            <div class="row">
                                <div class="col-md-12 text-center m-b-20">
                                    <label><input type="checkbox" name="amHeadCoach"> I am the <strong>Head Coach</strong> of this group</label>
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