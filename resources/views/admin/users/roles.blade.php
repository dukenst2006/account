@extends('layouts.master')

@section('title', $user->full_name.' Roles')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-title no-border">
                <div class="row">
                    <div class="col-md-1 col-sm-2 col-xs-2">
                        <img src="{{ Gravatar::src(Auth::user()->email, 69) }}"  alt="" width="69" height="69" />
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-10">
                        <h3 class="semi-bold p-t-10 p-b-10 m-l-15">{{ $user->full_name }}</h3>
                    </div>
                </div>
                <div class="b-grey b-b m-t-10"></div>
            </div>
            <div class="grid-body no-border">
                @include('partials.messages')
                {!! Form::open(['method' => 'post', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                <div class="row form-group">
                    <div class="col-md-4 col-md-offset-4 p-t-20">
                        <label class="form-label">Roles</label>
                        <div class="help">Some roles are not editable because they're controlled automatically by the system</div>
                        <div class="controls p-b-20">
                            @foreach($roles as $role)
                                <?php
                                    $fieldAttrs = ['id' => 'role-'.$role->id];
                                    if ($role->isEditable() == false) {
                                         $fieldAttrs['disabled'] = 'disabled';
                                    }
                                ?>
                                <div class="checkbox check-primary">
                                    {!! Form::checkbox('role['.$role->id.']', '1', old('role['.$role->id.']', $user->is($role->name)), $fieldAttrs) !!}
                                    <label for="role-{{ $role->id }}">{{ $role->display_name }}</label>
                                </div><br/>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center p-t-20">
                        <button class="btn btn-primary btn-cons" type="submit">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection