@extends('layouts.master')

@section('title', 'My Addresses')

@section('content')
    @include('partials.messages')
    <div class="content sm-gutter">
        <div class="row">
        @foreach(Auth::user()->addresses as $address)
            <div class="col-md-3 col-vlg-3 m-b-10">
                <div class="grid simple address-card">
                    <div class="grid-title no-border">
                        <h4>{{ $address->name }}</h4>
                        <div class="tools">
                            <form method='DELETE' action='address/{{ $address->id }}'>
                                <a href="address/{{ $address->id }}/edit" class="fa fa-edit"></a>
                                <a href="javascript:void(0);" onclick="$(this).closest('form').submit();" class="fa fa-trash-o"></a>
                            </form>
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
                    <div class="grid-body no-border">
                        Add new address
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection