@extends('layouts.master')

@section('title', 'Editing '.$teamSet->name)

@section('content')
    @include('partials.messages')

    <div class="content p-b-100">
        @if($teamSet->registeredWithTournament())
            @include('tournaments.partials.tournament-summary', [
                'tournament' => $teamSet->tournament,
                'hideBorder' => true
            ])
        @endif
        <div id="page" v-cloak>
            @include('teamset.edit-teamset', [
                'players'   => $players,
                'teamSet'   => $teamSet
            ])
        </div>
    </div>
    @if($teamSet->registeredWithTournament())
        <section style="position: absolute; bottom: 0px; width: 100%;" class="p-t-10">
            <div id='tournament-prompt'>
                <div id='fee-warning' class="text-center">
                    <div class="text-center p-t-10 alert alert-error" style="display: none;">
                        <p>One or more of your teams are ineligible for this tournament.  All teams must have between {{ $teamSet->tournament->settings->minimumPlayersPerTeam() }} and {{ $teamSet->tournament->settings->maximumPlayersPerTeam() }} players.</p>
                    </div>
                    <p>If this tournament has fees associated with adding players and/or teams, those fees must be paid before team changes are final.</p>
                    <a href="/tournaments/{{ $teamSet->tournament->slug }}/group" class="btn btn-primary btn-cons">Save & Continue</a>
                </div>
            </div>
        </section>
    @endif
@endsection

@includeVueJs
@if(app()->environment('production', 'staging'))
    @includeJs(https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js)
@else
    @includeJs(/assets/plugins/jquery-ui-touch/jquery.ui.touch-punch.min.js)
@endif

@includeJs(elixir('js/teamsets.js'))
@includeCss(elixir('css/teamsets.css'))

@jsData
    var teamSet = {!! $teamSet->toJson() !!},
        registeredWithTournament = {{ $teamSet->registeredWithTournament() ? 'true' : 'false' }},
        teamsEditable = {{ $teamSet->canBeEdited(Auth::user()) ? 'true' : 'false' }},
        minPlayersPerTeam = {{ $teamSet->registeredWithTournament() ? $teamSet->tournament->settings->minimumPlayersPerTeam() : 'null' }},
        maxPlayersPerTeam = {{ $teamSet->registeredWithTournament() ? $teamSet->tournament->settings->maximumPlayersPerTeam() : 'null' }};
@endjsData