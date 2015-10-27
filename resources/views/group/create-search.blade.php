@extends('layouts.master')

@section('title', 'New Group')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="grid simple">
                    <div class="grid-body no-border">
                        <h3 class="m-t-30 text-center">Creating a group</h3>
                        <p class="text-center">Before you create your group, do a quick search to make sure<br/>it hasn't already been created.</p>
                        <div class="m-t-20">
                            <form method="get">
                            <div class="input-group transparent col-md-12">
                                <input type="text" class="form-control" placeholder="Search groups by name" name="q" value="{{ Input::get('q') }}"/>
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                            </form>
                        </div>
                        @if(Input::has('q'))
                            <div class="row m-t-20">
                            @if(count($groups) > 0)
                                <ul>
                                    @foreach($groups as $group)
                                        <li class="m-b-10">
                                            <strong>{{ $group->name }}</strong> ({{ $group->program->abbreviation }})<br/>
                                            <span class="muted">{{ $group->meetingAddress }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                            <div class="text-center">
                                <div class="p-t-20 p-b-10 muted">No groups found</div>
                            <div>
                            @endif
                        </div>
                        <p class="m-t-20 text-center">
                            <a href="/group/create" class="btn btn-primary">I don't see my group</a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection