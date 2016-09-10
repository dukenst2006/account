@extends('layouts.master')

@section('title', 'Groups: Transfer Ownership')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-offset-2 col-md-8 col-sm-offset-2 col-sm-8">
                <div class="grid simple">
                    <div class="grid-body dataTables_wrapper">
                        {!! Form::open(['class' => 'form-horizontal', 'role' => 'form']) !!}
                        <h4 class="semi-bold">Transfer Ownership: {{ $group->name }}</h4>
                        <p>Before transferring a group, be sure you've done due diligence to make sure you <span style="font-style:italic">should</span> transfer the group.  Groups can only be transferred to users who have confirmed their email address.</p>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label class="form-label">Owner</label>
                                <span class="help"></span>
                                <div class="controls p-b-20">
                                    {!! Form::select('user_id', $users, old('user_id', $group->owner_id), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <p>The previous owner and new owner will be automatically notified of this transfer, so double check to make sure you've chosen the correct new owner.</p>
                        <div class="row">
                            <div class="col-md-12 text-center p-t-40">
                                <button class="btn btn-primary btn-cons" type="submit">Transfer</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection