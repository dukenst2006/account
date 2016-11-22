@extends('layouts.master')

@section('title', 'Individual Events - '.$tournament->name)

@section('content')
    <div class="content">
        <div class="row">
            @if (count($events) <= 2)
                <div class="col-md-6 col-md-offset-3">
            @elseif(count($events) <= 4)
                <div class="col-md-8 col-md-offset-2">
            @else
                <div class="col-md-12">
            @endif
                @include('tournaments.partials.tournament-summary', [
                    'tournament' => $tournament
                ])
                <div class="grid simple">
                    <div class="grid-title no-border">
                        <h4>Optional <span class="semi-bold">Events</span></h4>
                    </div>
                    <div class="grid-body no-border">
                        <div class="row p-t-10">
                            <div class="col-md-12">
                                <p>There are some optional events at this tournament.  You can sign up your players for these events below.  Signups for events with fees are not final until payment has been submitted.</p>
                                {!! Form::open([
                                    'class' => 'form-horizontal',
                                    'role' => 'form'
                                ]) !!}
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Player</th>
                                        @foreach ($events as $event)
                                            <th>
                                                {{ $event->type->name }}
                                                @if($event->isFree() === false)
                                                    <br/>{{ $event->displayPrice() }}
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                    @if($playerCount > 0)
                                    <tr>
                                        <th class="muted">Select All</th>
                                        @foreach ($events as $event)
                                            <th class="row-fluid">
                                                <div class="checkbox check-default">
                                                    {!! Form::checkbox('event-all-'.$event->id, 1, old('event-all-'.$event->id, false), [ 'id' => 'event-all-'.$event->id, 'class' => 'checkcolumn' ]) !!}
                                                    <label for="event-all-{{ $event->id }}"></label>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                    @endif
                                    </thead>
                                    <tbody>
                                    @if($playerCount > 0)
                                        @foreach($players as $player)
                                            <tr>
                                                <td class="v-align-middle">{{ $player->last_name }}, {{ $player->first_name }}</td>
                                            @foreach ($events as $event)
                                                <td>
                                                    <div class="checkbox check-default">
                                                        {!! Form::checkbox('event['.$event->id.']['.$player->id.']', 1, old('event['.$event->id.']['.$player->id.']', false), [ "id" => 'event-'.$event->id.'-player-'.$player->id ]) !!}
                                                        <label for="event-{{ $event->id }}-player-{{ $player->id }}"></label>
                                                    </div>
                                                </td>
                                            @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="7"><div class="muted m-t-40" style="font-style: italic">No players have been added to your teams</div></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                @if($playerCount > 0)
                                <div class="text-center">
                                    <button class="btn btn-primary btn-cons" type="submit">Save & Continue</button>
                                </div>
                                @endif
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@js
$(document).ready(function() {
    // check checkboxes in the corresponding column
    $('.checkcolumn').on('change', function () {
        var cellIndex = $(this).closest('th')[0].cellIndex+1,
            $el = $(this),
            $table =  $el.closest('table')
            $checkboxes = $table.find('td:nth-child('+cellIndex+') input[type=checkbox]');
        $checkboxes.attr('checked', $el.is(':checked'));
    });
});
@endjs