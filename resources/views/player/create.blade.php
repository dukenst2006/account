@extends('layouts.master')

@section('title', 'New Player')

@section('before-styles-end')
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker.min.css" rel="stylesheet" type="text/css"/>
@endsection
@section('includeJs')
    <script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
@endsection
@section('js')
    $(document).ready(function () {
        $('.input-append.date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    });
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>New <span class="semi-bold">Player</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        {!! Form::open(['url' => ['/player'], 'class' => 'form-horizontal', 'role' => 'form']) !!}
                            @include('player.form')
                            <div class="row">
                                <div class="col-md-12 text-center p-t-20">
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