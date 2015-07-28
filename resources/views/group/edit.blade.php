@extends('layouts.master')

@section('title', 'Editing '.$group->name)

@section('includeJs')
    <script src="/assets/js/group.js" type="text/javascript"></script>
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4 class="p-b-20">Edit <span class="semi-bold">{{$group->name}}</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::model($group, ['url' => ['/group/'.$group->id], 'role' => 'form', 'method' => 'PATCH']) !!}
                            @include('group.form')
                            <div class="checkbox check-danger b-t b-b b-grey p-t-10 p-b-10">
                                 {!! Form::checkbox('inactive', Carbon\Carbon::now()->toDateTimeString(), null, ['id' => 'group-inactive']) !!}
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