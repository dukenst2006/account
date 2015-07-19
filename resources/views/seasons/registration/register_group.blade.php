@extends('layouts.master')

@section('title', 'Find your group')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body no-border">
                @if(is_null($searchResults))
                    @include('group.nearby')
                @endif
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6 m-t-20">
                        <h4>Don't see your group? <span class="semi-bold">Search for one</span></h4>
                        {!! Form::open(['url' => ['/group/search'], 'role' => 'form', 'method' => 'get']) !!}
                        <div class="input-group transparent">
                            <input type="text" class="form-control" name="q" placeholder="Search all groups" value="{{ Input::get('q') }}" autofocus>
                              <span class="input-group-addon">
                                  <i class="fa fa-search"></i>
                              </span>
                        </div>
                        <input type="submit" value="Search" style="position: absolute; top: 0; left: 0; z-index: 0; width: 1px; height: 1px; visibility: hidden;" />
                        {!! Form::close() !!}
                    </div>
                </div>
                @if(!is_null($searchResults))
                <table class="table no-more-tables" style="margin-bottom: 0">
                    <thead>
                    @foreach($searchResults as $group)
                        <tr>
                            <td class="v-align-middle">
                                <strong>{{ $group->full_name }}</strong><br/>
                                <span class='muted'>{{ $group->meetingAddress->address_one }} {{ $group->meetingAddress->address_two }} {{ $group->meetingAddress->city }}, {{ $group->meetingAddress->state }} {{ $group->meetingAddress->zip_code }}</span>
                            </td>
                            <td class="v-align-middle">
                                <a href="/seasons/register/{{ $group->id }}">Register</a>
                            </td>
                        </tr>
                    @endforeach
                    </thead>
                </table>
                @endif
                <div class="form-actions text-center">
                    <h4 class="semi-bold">Can't find your group?</h4>
                    <p>That's ok, go ahead and register with National Bible Bowl and you can connect with your group later.</p>
                    <a href="/seasons/register" class="btn btn-primary btn-cons">Register</a>
                </div>
            </div>
        </div>
    </div>
@endsection