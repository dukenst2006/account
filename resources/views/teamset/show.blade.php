@extends('layouts.master')

@section('title', 'Editing '.$teamSet->name)

@section('content')
    <div class="content" id="page">
        @include('teamset.edit-teams')
    </div>
@endsection

@if(app()->environment('production', 'staging'))
    @includeJs(https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.10/vue.min.js)
    @includeJs(https://cdn.jsdelivr.net/vue.validator/2.0.0-alpha.6/vue-validator.min.js)
@else
    @includeJs(/assets/plugins/vuejs/vue-1.0.10.min.js)
    @includeJs(/assets/plugins/vuejs/vue-2.0.0-alpha.6-validator.min.js)
@endif

@includeJs(elixir('js/teamsets.js'))
@includeCss(elixir('css/teamsets.css'))

@jsData
    var teamSet = {!! $teamSet->toJson() !!}
@endjsData