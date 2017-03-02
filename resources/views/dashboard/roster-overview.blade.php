<div class="tiles white m-b-10">
    <div class="tiles-body">
        <div class="pull-left">
            <h4>Player <span class="semi-bold">Roster Overview</span></h4>
        </div>
        <div class="pull-right p-t-10">
            <button class="btn btn-info" data-toggle="modal" data-target="#playerRegistrationLinkModal">Get Registration Link</button>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            @if(isset($playerStats['byGender']) && count($playerStats['byGender']) > 0)
            <div class="col-md-6 col-sm-6">
                <div id="rosterByGender"></div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div id="rosterByGrade"></div>
            </div>
                @includeGoogleCharts
                @js
                    google.charts.load('current', {packages: ['corechart']});
                    google.charts.setOnLoadCallback(function() {
                        var data = google.visualization.arrayToDataTable([
                            ['Gender', 'Players'],
                        @foreach($playerStats['byGender'] as $genderData)
                            ['{{ \App\Presentation\Describer::describeGender($genderData['gender']) }}', {{ $genderData['total'] }}],
                        @endforeach
                        ]);

                        var chart = new google.visualization.PieChart(document.getElementById('rosterByGender'));
                        chart.draw(data, {
                            title: 'By Gender',
                            colors: ['{!! implode("','", \App\Presentation\Html::ACCENT_COLORS) !!}']
                        });

                        // ------------ byGrade ------------
                        var data = google.visualization.arrayToDataTable([
                        ['Grade', 'Players'],
                        @foreach($playerStats['byGrade'] as $gradeData)
                            ['{{ \App\Presentation\Describer::describeGrade($gradeData['grade']) }}', {{ $gradeData['total'] }}],
                        @endforeach
                        ]);

                        var chart = new google.visualization.PieChart(document.getElementById('rosterByGrade'));
                        chart.draw(data, {
                            title: 'By Grade',
                            colors: ['{!! implode("','", \App\Presentation\Html::ACCENT_COLORS) !!}']
                        });
                    });
                @endjs
        </div>
        <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <a href="/roster">{{ number_format($playerStats['total']) }} active players</a> this season among {{ number_format($familyCount) }} {{ $familyCount > 1 ? 'families' : 'family' }}
                </div>
            @else
                <div class="p-t-40 p-b-40 text-center muted" style="font-style:italic">Use the blue button in the top right corner to get a link you can give to parents them register with your group</div>
            @endif
        </div>
    </div>
</div>

 <div class="modal fade" id="playerRegistrationLinkModal" tabindex="-1" role="dialog" aria-labelledby="playerRegistrationLinkModal" aria-hidden="true" style="display: none;">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <br>
                 <i class="fa fa-pencil fa-7x"></i>
                 <h4 class="semi-bold">Distribute the below registration link.</h4>
                 <p class="no-margin">Your group is already searchable by parents, but if you send them this link we'll direct them right where they need to go!</p>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-12">
                         <input type="text" class="form-control text-center click-copy" value="{{ url(Session::group()->registrationReferralLink()) }}">
                         <button class="btn btn-block btn-primary btn-copy ripple" type="button">
                             <i class="fa fa-paste"></i>
                             <span>Copy link to clipboard</span>
                         </button>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>