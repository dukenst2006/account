<div class="tiles white m-b-10">
    <div class="tiles-body">
        <div class="pull-left">
            <h4>Player <span class="semi-bold">Roster Overview</span></h4>
        </div>
        <div class="pull-right p-t-10">
            <button class="btn btn-info" data-toggle="modal" data-target="#playerRegistrationLinkModal">Registration Link</button>
        </div>
        <div class="row">
            @foreach(Auth::user()->players as $index => $player)
            <div class="col-md-3 @if($index > 0) b-l b-grey @endif p-l-20">
                <h4 class="semi-bold">{{ $player->full_name }}</h4>
                <p>{!! HTML::genderIcon($player->gender) !!} {{ $player->gender }}</p>
                <p> {{ $player->age() }} years old</p>
                <p class="text-center">
                    <a href="/player/{{ $player->id }}/edit">[ Edit ]</a>
                </p>
            </div>
            @endforeach
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