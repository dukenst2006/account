@extends('layouts.master')

@section('title', 'New Group')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="grid simple">
                    <div class="grid-body no-border">
                        <h3 class="m-t-30 text-center">Creating a group</h3>
                        <p class="text-center">Before you create your group, do a quick search to make sure<br/>it hasn't already been created by someone else.</p>
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
                            <button class="btn btn-info m-r-20" data-toggle="modal" data-target="#groupAlreadyExistsModal">I see my group</button>
                            <a href="/group/create" class="btn btn-primary">I don't see my group</a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="groupAlreadyExistsModal" tabindex="-1" role="dialog" aria-labelledby="groupAlreadyExistsModal" aria-hidden="true" style="display: none; margin-top: 2em;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="semi-bold">My Group Already Exists</h4>
                    <div class="text-left">
                        <p>Creating the group anyways will make things very confusing for the parents of your players so please <span style="font-style:italic">do not create a duplicate group</span>.  If you need to obtain ownership of this group you have a few options:</p>
                        <ul>
                            <li>Contact the group owner and have them contact the national office at <strong>{{ config('biblebowl.officeEmail') }}</strong> requesting the group be transferred to you.</li>
                            <li>If you don't have any contact with the group owner, please contact us and we can help get the group transferred.</li>
                            <li>As a temporary measure, the existing owner can add you as a user via their group's settings.  This will allow you to manage the group, but you won't be the owner.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection