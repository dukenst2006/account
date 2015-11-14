@extends('layouts.master')

@section('title', $tournament->name.' - '.$tournament->season->name)

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h3 class="semi-bold p-t-10 p-b-10 m-l-15">{{ $tournament->name }}</h3>
                <div class="b-grey b-b m-t-10"></div>
            </div>
            <div class="grid-body no-border p-t-20">
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <h5><i class="fa fa-users"></i> <span class="semi-bold">Events</span></h5>
                        <table class="table no-more-tables">
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Price</th>
                            </tr>
                            @foreach ($tournament->events as $event)
                                <tr>
                                    <td><a href="/admin/tournaments/event/{{ $event->id }}">{{ $event->type->name }}</a></td>
                                    <td class="text-center">{{ is_null($event->price_per_participant) ? '-' : '$'.money_format($event->price_per_participant) }} / {{ ucwords($event->type->participant_type) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="text-center muted p-t-20" style="font-style:italic; font-size: 90%;">
                        Last Updated: {{ $tournament->updated_at->format('F j, Y, g:i a') }} |
                        Created: {{ $tournament->created_at->format('F j, Y, g:i a') }}
                </div>
            </div>
        </div>
    </div>
@endsection