@extends('layouts.master')

@section('title', 'Editing '.$player->full_name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div class="grid simple">
                    {!! Form::model($player, ['url' => ['/player/'.$player->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Player</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        @include('player.form')

                        @if($isRegistered)
                            <h4>{{ Session::season()->name }} <span class="semi-bold">Season Registration</span></h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Grade</label>
                                    <span class="help"></span>
                                    <div class="controls p-b-20">
                                        {!! Form::selectGrade('grade', old('grade', $registration->grade), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">T-Shirt Size</label>
                                    <span class="help"></span>
                                    <div class="controls">
                                        {!! Form::selectShirtSize('shirt_size', old('shirt_size', $registration->shirt_size), ['class' => 'form-control']) !!}<br/>
                                    </div>
                                </div>
                            </div>
                        @endif

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