@extends('layouts.master')

@section('title', 'Growth Charts')

@section('content')
    <div class="content">
        <div class="grid white simple">
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h4>Growth of <span class="semi-bold">Groups per Season</span></h4>
                        <p>Total number of groups who had players registered with them, regardless of their player's active/inactive status</p>
                        <div id="groupsByProgram" style="height: 200px"></div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>Growth of <span class="semi-bold">Players per Season</span></h4>
                        <p>"Quitters" are players who started off the season playing and then became "inactive" throughout the season</p>
                        <div id="playersByProgram" style="height: 200px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@includeCss(/assets/plugins/jquery-morris-chart/css/morris.css)

@if(App::environment('local'))
    @includeJs(http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js)
@else
    @includeJs(/assets/plugins/raphael/raphael-2.1.0-min.js)
@endif

@includeJs(/assets/plugins/jquery-morris-chart/js/morris.min.js)
@js
    <?php
    $beginnerColor = '#0AA699';
    $teenColor = '#0090D9';
    ?>
    Morris.Line({
        element: 'groupsByProgram',
        data: [
            @foreach($groupSummaryByProgram as $season)
                    { y: '{{ $season->name }}', a: {{ $season->beginner_count }}, b: {{ $season->teen_count }} },
            @endforeach
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Beginner', 'Teen'],
        lineColors:['{{ $beginnerColor }}','{{ $teenColor }}']
    });

    Morris.Line({
        element: 'playersByProgram',
        data: [
        @foreach($playerSummaryByProgram as $season)
            { y: '{{ $season->name }}', a: {{ $season->beginner_count }}, b: {{ $season->teen_count }}, c: {{ $season->beginner_quitters_count }}, d: {{ $season->teen_quitters_count }} },
        @endforeach
        ],
        xkey: 'y',
        ykeys: ['a', 'b', 'c', 'd'],
        labels: ['Beginner', 'Teen', 'Beginner Quitters', 'Teen Quitters'],
        lineColors:['{{ $beginnerColor }}','{{ $teenColor }}', '#F9BA46', '#ED7364']
    });
@endjs