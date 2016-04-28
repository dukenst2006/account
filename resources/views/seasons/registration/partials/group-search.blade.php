 <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6 m-t-20">
        {!! Form::open(['role' => 'form', 'method' => 'get']) !!}
        <p>Search for your group by church name, school, etc.</p>
        <div class="input-group transparent">
            <input type="text" class="form-control" name="q" placeholder="Search all groups" value="{{ Input::get('q') }}" autofocus>
              <span class="input-group-addon">
                  <i class="fa fa-search"></i>
              </span>
        </div>
        <input type="submit" value="Search" style="position: absolute; top: 0; left: 0; z-index: 0; width: 1px; height: 1px; visibility: hidden;" />
        {!! Form::close() !!}
    </div>
</div>
@if(!is_null($searchResults))
    <div class="row">
        @if(count($searchResults) > 0)
        @foreach($searchResults as $index => $group)
            <div class="col-md-4 col-sm-6">
                <div class="grid simple">
                    <div class="grid-body no-border m-t-20">
                        <h5><span class="semi-bold">{{ $group->name }}</span></h5>
                        <address>
                            {!! HTML::address($group->meetingAddress) !!}
                        </address>
                        <div class="p-t-10 text-center">
                            <a href="{{ str_replace('[ID]', $group->id, $actionUrl) }}" class="btn btn-primary btn-sm btn-small">{{ $actionButton }}</a>
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
        @else
            <div class="text-center">
                <div class="p-t-20 p-b-10 muted">No groups found</div>
            <div>
        @endif
    </div>
@endif