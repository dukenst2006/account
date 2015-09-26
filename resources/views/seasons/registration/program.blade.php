@extends('layouts.master')

@section('title', 'Choose your program')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body no-border">
                <h3 class="m-t-20">Which program are you registering player(s) for?</h3>
                <p>You can do this again if you need to register players for more than one program.</p>
                <div class="row m-t-30">
                    @foreach ($programs as $program)
                    <div class="col-md-4">
                        <a class="btn btn-primary text-center" style="display: block" href="/{{ $action }}/{{ $program->slug }}/search/group">
                            <h4 class="semi-bold text-white">{{ $program->name }}</h4>
                            <h6 class="no-margin text-white p-b-10">{{ $program->description }}</h6>
                        </a>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection