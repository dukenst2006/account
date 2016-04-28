@extends('layouts.master')

@section('title', 'Notification Settings')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-3 col-md-6 col-sm-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="p-t-10 p-b-10">Notification <span class="semi-bold">Preferences</span></h3>
                    </div>
                    <div class="grid-body no-border p-t-20">
                        @include('partials.messages')
                        {!! Form::open(['url' => ['/account/notifications'], 'role' => 'form', 'method' => 'PATCH']) !!}
                        <div class="row">
                            <div class="checkbox check-default">
                                <input id="notifyWhenUserJoinsGroup" type="checkbox" name="notifyWhenUserJoinsGroup" value="1"
                                        @if((Input::has('notifyWhenUserJoinsGroup') && Input::old('notifyWhenUserJoinsGroup') == 1) || $settings->shouldBeNotifiedWhenUserJoinsGroup())
                                            checked
                                        @endif>
                                <label for="notifyWhenUserJoinsGroup">Email me whenever a new player joins one of my groups</label>
                            </div>
                        </div>
                        <div class="row text-center p-t-20">
                            <button class="btn btn-primary btn-cons" type="submit">Save</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection