<div class="grid-title no-border">
    <h4 class="full-width">You're joining <span class="semi-bold">{{ $group->name }} ({{ $group->type() }})</span></h4>
</div>
<div class="grid-body no-border">
    <div class="row">
        <div class="col-md-6">
            <dl>
                <dt>Meets at:</dt>
                <dd>
                    <a href="http://maps.google.com/?q={{ $group->address }}" title="View on a map" target="_blank">
                        @include('partials.address', [
                            'address' => $group->address
                        ])
                    </a>
                </dd>
            </dl>
        </div>
        <div class="col-md-6">
            <dl>
                <dt>Contact:</dt>
                <dd>
                    {{ $group->owner->full_name }}<br/>
                    @if(!is_null($group->owner->phone))
                        <a href='tel:+1{{ $group->owner->phone }}'>{{ HTML::formatPhone($group->owner->phone) }}</a><br/>
                    @endif
                    <a href="mailto:{{ $group->owner->email }}">{{ $group->owner->email }}</a>
                </dd>
            </dl>
        </div>
    </div>
</div>