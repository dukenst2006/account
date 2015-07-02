@extends('layouts.master')

@section('title', 'Editing '.$group->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Group</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::model($player, ['url' => ['/group/'.$group->id], 'role' => 'form', 'method' => 'PATCH']) !!}
                            @include('group.form')
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