<div class="tiles white m-b-10">
    <div class="tiles-body">
        <div class="pull-left">
            <h4>Player <span class="semi-bold">Roster Overview</span></h4>
        </div>
        <div class="pull-right p-t-10">
            <button class="btn btn-info" data-toggle="modal" data-target="#playerRegistrationLinkModal">Registration Link</button>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-3 col-md-offset-2 col-sm-6 col-xs-6 col-xs-offset-0">
                <span>By Gender</span>
                <div id="rosterByGender" style="height: 200px"></div>
            </div>
            <div class="col-md-3 col-md-offset-1 col-sm-6 col-xs-6 col-xs-offset-0">
                <span>By Grade</span>
                <div id="rosterByGrade" style="height: 200px"></div>
            </div>
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
@includeCss(/assets/plugins/jquery-morris-chart/css/morris.css)

@if(App::environment('local'))
    @includeJs(http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js)
@else
    @includeJs(/assets/plugins/raphael/raphael-2.1.0-min.js)
@endif

@includeJs(/assets/plugins/jquery-morris-chart/js/morris.min.js)
@js
    Morris.Donut({
        element: 'rosterByGender',
        resize: true,
        data: [
        @foreach($playerStats['byGender'] as $genderData)
            {label: "{{ \BibleBowl\Presentation\Describer::describeGender($genderData['gender']) }}", value: {{ $genderData['total'] }}},
        @endforeach
        ]
    });

    Morris.Donut({
        element: 'rosterByGrade',
        data: [
        @foreach($playerStats['byGrade'] as $gradeData)
            {label: "{{ \BibleBowl\Presentation\Describer::describeGrade($gradeData['grade']) }}", value: {{ $gradeData['total'] }}},
        @endforeach
        ]
    });
@endjs