@extends('layouts.master')

@section('title', 'Find your group')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body no-border">

                @if(!Input::has('q'))
                    @include('group.nearby', [
                        'groupLinks' => [
                            'registerLink' => 'Select this group'
                        ]
                    ])
                @endif

                @include('seasons.registration.search_group', [
                    'groupLinks' => [
                        'registerLink' => 'Select this group'
                    ]
                ])

                <div class="form-actions text-center">
                    <h4 class="semi-bold">Can't find your group?</h4>
                    <p>That's ok, go ahead and register with National Bible Bowl and you can connect with your group later.</p>
                    <a href="/register/group" class="btn btn-primary btn-cons">Register</a>
                </div>
            </div>
        </div>
    </div>
@endsection