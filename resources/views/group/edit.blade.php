@extends('layouts.master')

@section('title', 'Editing '.$group->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-1 col-md-10 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $group->name }}</h3>
                    </div>
                    <div class="bar">
                        <div class="bar-inner">
                            <ul>
                                <li
                                @if(Route::current()->getUri() == 'group/{group}/edit')
                                    class="active bold"
                                @endif>
                                    <a href="/group/{{ $group->id }}/edit">Profile</a>
                                </li>
                                {{--<li
                                @if(Route::current()->getUri() == 'group/{group}/integrations')
                                    class="active bold"
                                @endif>
                                    <a href="#">Integrations</a>
                                </li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20"></div>
                    <div class="grid-body no-border p-t-20">
                        @include('partials.messages')
                        {!! Form::model($group, ['url' => ['/group/'.$group->id], 'role' => 'form', 'method' => 'PATCH']) !!}
                            @include('group.form')
                            <div class="checkbox check-danger">
                                {!! Form::checkbox('inactive', 1, !$group->isActive(), ['id' => 'group-inactive']) !!}
                                <label for="group-inactive">Make inactive <span class="muted p-l-10">- prevents others from being able to join this group.</span></label>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center p-t-40">
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