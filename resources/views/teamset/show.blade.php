@extends('layouts.master')

@section('title', $teamSet->name)

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h3 class="semi-bold p-t-10 p-b-10 m-l-15">{{ $teamSet->name }}</h3>
                <div class="b-grey b-b m-t-10"></div>
            </div>
            <div class="grid-body no-border p-t-20">
            </div>
        </div>
    </div>
@endsection