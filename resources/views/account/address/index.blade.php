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
            <div class="col-md-3 col-vlg-3 m-b-10 address-card">
                <div class="grid simple {{((Auth::user()->primary_address_id === $address->id)) ? ' vertical red' : ''}}">
                    <div class="grid-title">
                        <h4>
                            <span class="address-name semi-bold">
                                {{ $address->name }}
                            </span>
                        </h4>
                        <div class="tools">
                            {!! Form::open(['url' => '/account/address/'.$address->id, 'method' => 'delete']) !!}
                            <div class="btn-group"> <a class="btn dropdown-toggle address-ops" data-toggle="dropdown" href="#"><span class="badge pull-right"><i class="fa fa-gear"></i></span></a>
                                <ul class="dropdown-menu pull-right">
                                    @if (Auth::user()->primary_address_id !== $address->id)
                                        <li><a id="set-primary-{{$address->id}}" href="/account/address/{{ $address->id }}/makePrimary" class="control-set-default">Make Primary</a></li>
                                    @endif
                                    <li><a href="/account/address/{{ $address->id }}/edit" class="control-edit">Edit</a></li>
                                    <li><a onclick="$(this).closest('form').submit();" class="control-delete">Delete</a></li>
                                </ul>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        @if(Auth::user()->primary_address_id === $address->id)
                            <span data-toggle="tooltip" data-placement="left" title="Visible to any groups your children play for." class="badge badge-important pull-right">Primary</span>
                        @endif
                    </div>
                    <div class="grid-body">
                        @include('partials.address')
                    </div>
                </div>
            </div>
        @endforeach
            <div class="col-md-3 col-vlg-3 m-b-10 address-card">
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