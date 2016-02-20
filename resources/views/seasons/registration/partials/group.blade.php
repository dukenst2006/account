<h4 class="semi-bold no-margin">
    {{ $group->name }}
    <a href="/{{ $action }}/{{ $group->program->slug }}/search/group" style="font-size:12px;font-weight:normal" id="group-change">(Change)</a>
</h4>
<div class="row m-t-10">
    <div class="col-md-6 col-sm-6 col-xs-6">
        <dl style="margin-bottom: 0">
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
    <div class="col-md-6 col-sm-6 col-xs-6">
        <dl style="margin-bottom: 0">
            <dt>Head Coach:</dt>
            <dd>
                @include('partials.user-contact', [
                    'user' => $group->owner
                ])
            </dd>
        </dl>
    </div>
</div>