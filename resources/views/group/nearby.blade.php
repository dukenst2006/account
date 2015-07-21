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
                <strong>{{ $group->full_name }}</strong><br/>
                <span class='muted'>{{ $group->meetingAddress->address_one }} {{ $group->meetingAddress->address_two }} {{ $group->meetingAddress->city }}, {{ $group->meetingAddress->state }} {{ $group->meetingAddress->zip_code }}</span>
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