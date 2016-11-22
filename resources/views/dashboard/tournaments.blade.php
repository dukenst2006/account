@if($tournaments->count() > 0)
    <div class="col-md-6">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4 class="bold">Upcoming Tournaments</h4>
            </div>
            <div class="grid-body no-border" style="padding-bottom:0;">
                @foreach($tournaments as $tournament)
                    <div class="row b-t b-grey p-t-10 p-b-10">
                        <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0">
                            <a href="/tournaments/{{ $tournament->slug }}" class="bold">{{ $tournament->name }}</a>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-5 text-center" style="padding-right: 0">
                            <a href="/tournaments/{{ $tournament->slug }}/group">Manage Registration</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif