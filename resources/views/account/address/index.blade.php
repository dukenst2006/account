@extends('layouts.master')

@section('title', 'My Addresses')

@section('includeJs')
    <script src="/assets/js/accounts.js" type="text/javascript"></script>
@endsection

@section('content')
    <div class="content sm-gutter">
        @include('partials.messages')
        <div class="row">
        @foreach(Auth::user()->addresses as $address)
            <div class="col-md-3 col-vlg-3 m-b-10">
                <div class="grid simple address-card {{((Auth::user()->primary_address_id === $address->id)) ? ' vertical red' : ''}}">
                    <div class="grid-title">
                        <h4>
                            <span class="address-name">
                                {{ $address->name }}
                            </span>
                        </h4>
                        <div class="tools">
                            {!! Form::open(['url' => '/account/address/'.$address->id, 'method' => 'delete']) !!}
                            <div class="btn-group"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="badge pull-right"><i class="fa fa-gear"></i></span></a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="/account/address/{{ $address->id }}/edit" class="control">Edit</a></li>
                                    <li><a onclickmaster="$(this).closest('form').submit();" class="control">Delete</a></li>
                                    @if (Auth::user()->primary_address_id !== $address->id)
                                        <li><a id="set-primary-{{$address->id}}" href="/account/address/{{ $address->id }}/makePrimary">Make Primary</a></li>
                                    @endif
                                </ul>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        @if(Auth::user()->primary_address_id === $address->id)
                            <span data-toggle="tooltip" data-placement="left" title="Super helpful tooltip that Ben can reconfigure to be more helpful." class="badge badge-important pull-right">Primary</span>
                        @endif
                    </div>
                    <div class="grid-body">
                        @include('partials.address')
                    </div>
                </div>
            </div>
        @endforeach
            <div class="col-md-3 col-vlg-3 m-b-10">
                <div class="grid simple address-card">
                    <a href="/account/address/create" class="grid-body no-border button-grid text-center">
                        <span class="fa fa-plus"></span><br/>
                        New Address
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection