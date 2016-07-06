<div class="row b-b b-grey p-b-10">
    <div class="col-md-3 p-t-20">
        Group:
    </div>
    <div class="col-md-9">
        <h5 class="semi-bold">{{ $group->name }}</h5>
        <span class="help">{{ $group->meetingAddress->city }}, {{ $group->meetingAddress->state }}</span>
    </div>
</div>