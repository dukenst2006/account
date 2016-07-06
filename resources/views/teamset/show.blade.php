@extends('layouts.master')

@section('title', 'Editing '.$teamSet->name)

@section('content')
    <div class="content" id="page" v-cloak>
        @include('teamset.edit-teamset', [
            'players'   => $players,
            'teamSet'   => $teamSet
        ])
    </div>
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
    var teamSet = {!! $teamSet->toJson() !!}
@endjsData