@extends('layouts.master')

@section('title', 'Invite Tournament Coordinator')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $tournament->name }}</h3>
                    </div>
                    <div class="bar">
                        <div class="bar-inner">
                            @include('tournaments.admin.menu-partial', [
                                'selected' => 'Coordinators'
                            ])
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20"></div>
                    <div class="grid-body no-border p-t-20">
                        {!! Form::open(['role' => 'form']) !!}
                        @include('partials.messages')
                        <div class="row">
                            <div class="form-group col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 m-b-10">
                                <p>By inviting other coordinators, they'll be able to view/manage your tournament's:</p>
                                <ul class="m-b-20">
                                    <li>Description/Details</li>
                                    <li>Registrations</li>
                                </ul>

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