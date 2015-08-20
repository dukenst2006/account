@extends('layouts.master')

@section('title', 'Editing '.$season->name.' Registration')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">{{ $season->name }} Registration</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        Player: {{ $player->full_name }}
                        {!! Form::open(['class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Grade {{ $season->pivot->grade }}</label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        {!! Form::selectGrade('grade', old('grade', $season->pivot->grade), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">T-Shirt Size {{ $season->pivot->shirt_size }}</label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        {!! Form::selectShirtSize('shirt_size', old('shirt_size', $season->pivot->shirt_size), ['class' => 'form-control']) !!}<br/>
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