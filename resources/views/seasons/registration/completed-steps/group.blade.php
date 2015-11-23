<div class="row">
    <div class="col-md-2">
        Group
    </div>
    <div class="col-md-10">
        @if(is_null($group))
            <p class="muted">None</p>
        @else
            <h4 class="semi-bold no-margin">
                {{ $group->name }}
                <a href="/{{ $action }}/{{ $group->program->slug }}/search/group?noRedirect=1" style="font-size:12px;font-weight:normal" id="group-change">(Change)</a>
            </h4>
            <div class="row m-t-10">
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
                        <dt>Head Coach:</dt>
                        <dd>
                            @include('partials.user-contact', [
                                'user' => $group->owner
                            ])
                        </dd>
                    </dl>
                </div>
            </div>
        @endif
    </div>
</div>