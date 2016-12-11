@extends('layouts.master')

@section('title', 'Player Reports')

@section('content')
    <div class="content">
        <div class="grid white simple">
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-6 p-t-20">
                        <h2>Season</h2>
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
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4 text-center">
                        <h2 class="semi-bold text-primary no-margin p-t-35 p-b-10">{{ number_format(\BibleBowl\Player::achievedMemoryMaster($currentSeason)->count()) }}</h2>
                        <div class="tiles-title blend p-b-25">
                            MEMORY MASTER ACHIEVERS
                            <a href="/admin/reports/export-memory-master" class="btn btn-xs btn-mini">Download CSV</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 p-t-20">
                        <h4>Player Statistics</h4>
                        <p>Figures are based on active players</p>
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
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3">
                        <div id="bySchool"></div>
                        @js
                        google.charts.setOnLoadCallback(function() {
                            var data = google.visualization.arrayToDataTable([
                                ['School', 'Players'],
                                @foreach($bySchoolSegment as $description => $count)
                                    ['{{ $description }} School', {{ $count }}],
                                @endforeach
                            ]);

                            var chart = new google.visualization.PieChart(document.getElementById('bySchool'));
                            chart.draw(data, {
                                title: 'By School Segment',
                                colors: ['{!! implode("','", \BibleBowl\Presentation\Html::ACCENT_COLORS) !!}']
                            });
                        });
                        @endjs
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 p-t-20">
                        <h4>Group Statistics</h4>
                        <p>Figures are based on groups with active players</p>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div id="groupsByProgram"></div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div id="groupsByType"></div>
                    </div>

                    @includeGoogleCharts
                    @js
                    google.charts.setOnLoadCallback(function() {
                        // ------------ groupsByProgram ------------
                        var data = google.visualization.arrayToDataTable([
                            ['Program', 'Groups'],
                            @foreach($groupStats['byProgram'] as $programData)
                                ['{{ $programData->program->name }}', {{ $programData->total }}],
                            @endforeach
                        ]);

                        var chart = new google.visualization.PieChart(document.getElementById('groupsByProgram'));
                            chart.draw(data, {
                            title: 'By Program',
                            colors: ['{!! implode("','", \BibleBowl\Presentation\Html::ACCENT_COLORS) !!}']
                        });

                        // ------------ groupsByType ------------
                        var data = google.visualization.arrayToDataTable([
                            ['Type', 'Groups'],
                            @foreach($groupStats['byType'] as $programData)
                                ['{{ $programData->type->name }}', {{ $programData->total }}],
                            @endforeach
                        ]);

                        var chart = new google.visualization.PieChart(document.getElementById('groupsByType'));
                            chart.draw(data, {
                            title: 'By Type',
                            colors: ['{!! implode("','", \BibleBowl\Presentation\Html::ACCENT_COLORS) !!}']
                        });
                    });
                    @endjs
                </div>
            </div>
        </div>
    </div>
@endsection