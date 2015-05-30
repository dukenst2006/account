@extends('layouts.master')

@section('title', 'Editing '.$player->full_name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Player</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::model($player, ['url' => ['/player/'.$player->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                            @include('player.form')
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