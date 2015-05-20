@extends('layouts.master')

@section('title', 'My Addresses')

@section('content')
    <div class="content sm-gutter">
        @include('partials.messages')
        <div class="row">
        @foreach(Auth::user()->addresses as $address)
            <div class="col-md-3 col-vlg-3 m-b-10">
                <div class="grid simple address-card">
                    <div class="grid-title no-border">
                        <h4>{{ $address->name }}</h4>
                        <div class="tools">
                            {!! Form::open(['url' => '/account/address/'.$address->id, 'method' => 'delete']) !!}
                                <a href="/account/address/{{ $address->id }}/edit" class="fa fa-edit"></a>
                                <a href='#' onclick="$(this).closest('form').submit();" class="fa fa-trash-o"></a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="grid-body no-border">
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