@extends('layouts.master')

@section('title', 'Editing '.$group->name)

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-1 col-md-10 col-sm-10 col-sm-offset-1 col-xs-12 horizontal-menu">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h3 class="semi-bold p-t-10 p-b-10">{{ $group->name }}</h3>
                    </div>
                    <div class="bar">
                        <div class="bar-inner">
                            @include('group.menu-partial', [
                                'selected' => 'profile'
                            ])
                        </div>
                    </div>
                    <div class="grid-body no-border p-t-20"></div>
                    <div class="grid-body no-border p-t-20">
                        @include('partials.messages')
                        {!! Form::model($group, ['url' => ['/group/'.$group->id], 'role' => 'form', 'method' => 'PATCH']) !!}
                            @include('group.form')
                            @if($group->program_id == \App\Program::BEGINNER)
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="form-label">Sharing Roster</label>
                                        <span class="help">We'll automatically send contact information for your 5th graders to a teen group at the end of a season.  This can be helpful if you have a teen group that your older players may play with next season.</span>
                                        <div class="controls p-b-10 row">
                                            <div class="controls">
                                                <label>{!! Form::selectGroup(\App\Program::TEEN, 'group_id', old('group_id', ($settings->hasGroupToShareRosterWith() ? $settings->groupToShareRosterWith()->id : null)), ['class' => 'form-control'], true) !!}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="checkbox check-danger">
                                {!! Form::checkbox('inactive', 1, !$group->isActive(), ['id' => 'group-inactive']) !!}
                                <label for="group-inactive">Inactive <span class="muted p-l-10">- prevents others from being able to join this group.</span></label>
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