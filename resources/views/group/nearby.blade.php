<div class="row">
    @foreach($nearbyGroups as $index => $group)
        <div class="col-md-4 col-sm-6">
            <div class="grid simple">
                <div class="grid-body">
                    <h5><span class="semi-bold">{{ $group->name }}</span></h5>
                    <address>
                        {!! Html::address($group->meetingAddress) !!}
                    </address>
                    <div class="p-t-10 text-center">
                        <a href="{{ str_replace('[ID]', $group->id, $actionUrl) }}" class="btn btn-primary btn-sm btn-small" id="select-nearby-group-{{ $group->id }}">{{ $actionButton }}</a>
                    </div>
                </div>
            </div>
        </div>
        @if($index % 3 == 2)
            <div class="clearfix visible-md-block"></div>
        @elseif($index % 2 == 1)
            <div class="clearfix visible-sm-block"></div>
        @endif
    @endforeach
</div>