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
                    <div class="col-md-3 col-md-offset-2 col-sm-6 col-xs-6 col-xs-offset-0">
                        <h4>By Gender</h4>
                        <div id="byGender" style="height: 200px"></div>
                    </div>
                    <div class="col-md-3 col-md-offset-1 col-sm-6 col-xs-6 col-xs-offset-0">
                        <h4>By Grade</h4>
                        <div id="byGrade" style="height: 200px"></div>
                    </div>

                    @includeMorris
                    @js
                        Morris.Donut({
                            element: 'byGender',
                            resize: true,
                            data: [
                            @foreach($playerStats['byGender'] as $genderData)
                                {label: "{{ \BibleBowl\Presentation\Describer::describeGender($genderData['gender']) }}", value: {{ $genderData['total'] }}},
                            @endforeach
                            ]
                        });

                        Morris.Donut({
                            element: 'byGrade',
                            data: [
                            @foreach($playerStats['byGrade'] as $gradeData)
                                {label: "{{ \BibleBowl\Presentation\Describer::describeGrade($gradeData['grade']) }}", value: {{ $gradeData['total'] }}},
                            @endforeach
                            ]
                        });
                    @endjs
                </div>
            </div>
        </div>
    </div>
@endsection