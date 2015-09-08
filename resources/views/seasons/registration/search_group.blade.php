 <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6 m-t-20">
        <h4>Don't see your group? <span class="semi-bold">Search for one</span></h4>
        {!! Form::open(['role' => 'form', 'method' => 'get']) !!}
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
<table class="table no-more-tables" style="margin-bottom: 0">
    <thead>
    @foreach($searchResults as $group)
        <tr>
            <td class="v-align-middle">
                <strong>{{ $group->name }} ({{ $group->type() }})</strong><br/>
                <span class='muted'><span class="fa fa-map-marker"></span> Meets @ {{ $group->meetingAddress }}</span>
            </td>
            <td class="v-align-middle">
                @foreach($groupLinks as $method => $label)
                    <a href="{{ $group->{$method}() }}">{{ $label }}</a>
                @endforeach
            </td>
        </tr>
    @endforeach
    </thead>
</table>
@endif