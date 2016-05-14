@extends('layouts.master')

@section('title', 'Editing '.$event->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="grid simple">
                    {!! Form::model($event, ['route' => ['tournaments.events.update', $tournament->id, $event->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Tournament Event</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')

                        @include('tournaments.events.form')

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