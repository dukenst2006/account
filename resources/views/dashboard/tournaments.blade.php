@if($tournaments->count() > 0)
    <div class="col-md-6">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4 class="bold">Tournaments</h4>
            </div>
            <div class="grid-body no-border" style="padding-bottom:0;">
                @foreach($tournaments as $tournament)
                    <div class="row b-t b-grey p-t-5 p-b-5">
                        <div class="col-md-7 col-sm-7 col-xs-7">
                            <a href="/tournaments/{{ $tournament->slug }}" class="bold">{{ $tournament->name }}</a>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-5 text-center">
                            <a href="/tournaments/{{ $tournament->slug }}/group">Manage Registration</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif