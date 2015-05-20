@extends('layouts.master')

@section('title', 'Editing '.$address->name.' Address')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Address</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::model($address, ['url' => ['/account/address/'.$address->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                            @include('account.address.form')
                            <div class="row">
                                <div class="col-md-12 text-center">
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