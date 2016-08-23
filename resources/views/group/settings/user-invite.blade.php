@extends('layouts.master')

@section('title', 'Group Invite User')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $group->name }}</h3>
                    </div>
                    <div class="bar">
                        <div class="bar-inner">
                            @include('group.menu-partial', [
                                'selected' => 'users'
                            ])
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20"></div>
                    <div class="grid-body no-border p-t-20">
                        {!! Form::open(['role' => 'form']) !!}
                        @include('partials.messages')
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label">E-Mail Address</label>
                                <span class="help"></span>
                                <div class="controls">
                                    <div class="input-with-icon right">
                                        <i class="icon-email"></i>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center p-t-20">
                                <button class="btn btn-primary btn-cons" type="submit">Send Invitation</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection