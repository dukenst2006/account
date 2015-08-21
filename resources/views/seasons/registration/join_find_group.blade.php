@extends('layouts.master')

@section('title', 'Find your group')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body no-border">

                @if(!is_null($familiarGroup))
                    @include('seasons.registration.partials.familiar_group_prompt', [
                        'group'             => $familiarGroup,
                        'actionUrl'         => '/join/group/'.$familiarGroup->id,
                        'actionButton'      => 'Join '.$familiarGroup->name
                    ])
                @endif

                @if(!Input::has('q'))
                    @include('group.nearby', [
                        'groupLinks' => [
                            'joinLink' => 'Join this group'
                        ]
                    ])
                @endif

                @include('seasons.registration.search_group', [
                    'groupLinks' => [
                        'joinLink' => 'Join this group'
                    ]
                ])
            </div>
        </div>
    </div>
@endsection