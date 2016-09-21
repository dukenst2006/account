<div class="grid simple vertical blue">
    <div class="grid-title no-border">
        <h4 class="bold">Getting Started</h4>
    </div>
    <div class="grid-body no-border" style="padding-bottom:0;">
        @if(!Auth::user()->isA(\BibleBowl\Role::GUARDIAN))
        <div class="row">
            <div class="col-md-7 col-sm-7">
                <h4 class="semi-bold">For Parents</h4>
                <p class="text-gray">Add your children who are eligible to play</p>
            </div>
            <div class="col-md-5 col-sm-5">
                <div class="m-t-20">
                    <a class="btn btn-primary btn-cons" href="/player/create" id="add-students">Add my student(s)</a>
                </div>
            </div>
        </div>
        @endif
        @if(!Auth::user()->isA(\BibleBowl\Role::HEAD_COACH))
        <div class="row
            @if(!Auth::user()->isA(\BibleBowl\Role::GUARDIAN))
                b-grey b-t
            @endif
                ">
            <div class="col-md-7 col-sm-7">
                <h4 class="semi-bold">For Head Coaches</h4>
                <p class="text-gray">Add your group so that players can register with you</p>
            </div>
            <div class="col-md-5 col-sm-5">
                <div class="m-t-20">
                    <a class="btn btn-primary btn-cons" href="/group/create/search">Add my group</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>