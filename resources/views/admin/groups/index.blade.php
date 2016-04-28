@extends('layouts.master')

@section('title', 'Groups')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body dataTables_wrapper">
                <form method="get">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="semi-bold">Groups</h4>
                    </div>
                    <div class="input-group transparent col-md-3 col-md-offset-9">
                        <input type="text" class="form-control" placeholder="Search by name" name="q" value="{{ Input::get('q') }}"/>
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
                </form>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="col-md-6">Name</th>
                            <th class="col-md-3 text-center">Owner</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($groups) > 0)
                        @foreach ($groups as $group)
                            <tr>
                                <td>
                                    <a href="/admin/groups/{{ $group->id }}" class="semi-bold">{{ $group->name }}</a>
                                    @if($group->isActive() === false)
                                        <span class="text-error">Inactive</span>
                                    @endif
                                    <div>
                                        {{ $group->meetingAddress->city }}, {{ $group->meetingAddress->state }} - {{ $group->program->abbreviation }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="/admin/users/{{ $group->owner->id }}">{{ $group->owner->full_name }}</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! HTML::pagination($groups) !!}
            </div>
        </div>
    </div>
@endsection