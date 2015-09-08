<br/>
<div class="alert alert-block alert-info fade in">
    <button type="button" class="close" data-dismiss="alert"></button>
    <h4 class="alert-heading"><i class="icon-warning-sign"></i> Looks like you'll save some time</h4>
    <p> You followed a registration link from <strong>{{ $group->name }} ({{ $group->type() }})</strong> so you can skip the options below and get right to the good stuff.</p>
    <div class="button-set">
        <a href="{{ $actionUrl }}" class="btn btn-info btn-cons" type="button">{{ $actionButton }}</a>
    </div>
</div>