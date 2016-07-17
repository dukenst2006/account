@extends('layouts.master')

@section('title', 'Player Reports')

@section('content')
    <div class="content">
        <div class="grid white simple">
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-6 p-t-20">
                        <h2>Player Statistics</h2>
                        <p>All figures are based on active players</p>
                    </div>
                    <div class="col-md-6 p-t-30 text-right">
                        <div class="btn-group"> <a class="btn btn-info dropdown-toggle btn-demo-space" data-toggle="dropdown" href="#"> {{ $currentSeason->name }} Season <span class="caret"></span> </a>
                            <ul class="dropdown-menu">
                                @foreach ($seasons as $season)
                                    <li><a href="?seasonId={{ $season->id }}">{{ $season->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div id="byGender"></div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div id="byGrade"></div>
                    </div>

                    @includeGoogleCharts
                    @js
                        google.charts.load('current', {packages: ['corechart']});
                        google.charts.setOnLoadCallback(function() {
                            var data = google.visualization.arrayToDataTable([
                                ['Gender', 'Players'],
                                @foreach($playerStats['byGender'] as $genderData)
                                    ['{{ \BibleBowl\Presentation\Describer::describeGender($genderData['gender']) }}', {{ $genderData['total'] }}],
                                @endforeach
                            ]);

                            var chart = new google.visualization.PieChart(document.getElementById('byGender'));
                            chart.draw(data, {
                                title: 'By Gender',
                                colors: ['{!! implode("','", \BibleBowl\Presentation\Html::ACCENT_COLORS) !!}']
                            });

                            // ------------ byGrade ------------
                            var data = google.visualization.arrayToDataTable([
                                ['Grade', 'Players'],
                                @foreach($playerStats['byGrade'] as $gradeData)
                                    ['{{ \BibleBowl\Presentation\Describer::describeGrade($gradeData['grade']) }}', {{ $gradeData['total'] }}],
                                @endforeach
                            ]);

                            var chart = new google.visualization.PieChart(document.getElementById('byGrade'));
                            chart.draw(data, {
                                title: 'By Grade',
                                colors: ['{!! implode("','", \BibleBowl\Presentation\Html::ACCENT_COLORS) !!}']
                            });
                        });
                    @endjs
                </div>
            </div>
        </div>
    </div>
@endsection