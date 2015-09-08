<div class="row">
    <div class="col-md-12 m-t-20">
        <h4>Groups <span class="semi-bold">Nearby</span></h4>
    </div>
</div>
<table class="table no-more-tables" style="margin-bottom: 0">
    <thead>
    @foreach($nearbyGroups as $group)
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