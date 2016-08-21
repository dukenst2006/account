@extends('layouts.master')

@section('title', 'Settings')

@includeDatePicker
@js
    $(document).ready(function () {
        $('.input-append.date').datepicker({
            format: 'M d',
            autoclose: true
        });
    });
@endjs

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div class="grid simple">
                    {!! Form::open(['url' => ['/admin/settings'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                    <div class="grid-title no-border">
                        <h4>Edit <span class="semi-bold">Settings</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        @include('partials.messages')
                        <div class="row">
                            <div class="col-md-12 p-b-20">
                                <label class="form-label">Season Rollover Date</label>
                                <span class="help">The season will roll to the next season on this date</span>
                                <div class="controls p-b-20">
                                    <div class="input-append success date col-md-10 col-lg-6 no-padding" data-date="{{ $seasonEnd->format('M j') }}">
                                        {!! Form::text('season_end', old('season_end', $seasonEnd->format('M j')), ['class' => 'form-control']) !!}
                                        <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 p-b-20">
                                <label class="form-label">Registration Fees</label>
                                <span class="help"></span>
                                <div class="controls p-b-20">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Program</th>
                                            <th class="text-center" style="width:150px">Fee</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($programs as $program)
                                        <tr>
                                            <td>{{ $program->name }}</td>
                                            <td>
                                                {!! Form::money("program[".$program->id."][registration_fee]", old("program[".$program->id."][register]", $program->registration_fee), [ 'required', 'class' => 'form-control' ]) !!}
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary btn-cons" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection