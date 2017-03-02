@extends('layouts.master')

@section('title', 'Registration Surveys')

@section('content')
    <div class="content">
        <div class="grid white simple">
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-6 p-t-20">
                        <h2>Registration Surveys</h2>
                        <p>Questions asked of all users when they register</p>
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
                    @includeGoogleCharts
                    @js
                        google.charts.load('current', {packages: ['corechart', 'bar']});
                    @endjs

                    @foreach($questions as $question)
                    <div class="col-md-12 m-b-40">
                        <h3>{{ $question['question']->question }}</h3>
                        @if(count($question['metrics']) > 0)
                            <div id="question-{{ $question['question']->id }}"></div>
                            @js
                            google.charts.setOnLoadCallback(function() {
                                var data = new google.visualization.arrayToDataTable([
                                    ['Answers', 'Responses'],
                                    @foreach($question['metrics'] as $idx => $metric)
                                        ["{{ $metric['answer'] }}", '{{ $metric['total'] }}'],
                                    @endforeach
                                ]);

                                var chart = new google.charts.Bar(document.getElementById('question-{{ $question['question']->id }}'));
                                chart.draw(data, {
                                    title: 'Chess opening moves',
                                    legend: { position: 'none' },
                                    bars: 'horizontal', // Required for Material Bar Charts.
                                    bar: {
                                        groupWidth: 20
                                    },
                                    axes: {
                                        x: {
                                            0: {
                                                side: 'top',
                                                label: 'Responses'
                                            } // Top x-axis.
                                        }
                                    },
                                    'colors': ['{!! implode("','", \App\Presentation\Html::ACCENT_COLORS) !!}']
                                    });
                                });
                            @endjs
                        @else
                            <div class="m-t-30 m-b-30 help text-center">There haven't been any responses to this question</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection