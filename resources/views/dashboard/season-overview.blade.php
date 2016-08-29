<div class="tiles white add-margin">
    <div class="p-t-20 p-l-20 p-r-20 p-b-20">
        <div class="row xs-p-b-20">
            <h3 class="text-center">{{ Session::season()->name }} Season</h3>
            <div class="col-md-4 col-sm-4 text-center">
                <a href="/admin/reports/growth">
                    <h2 class="semi-bold text-primary no-margin p-t-35 p-b-10">{{ number_format($groupCount) }}</h2>
                    <div class="tiles-title blend p-b-25">GROUPS</div>
                </a>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-4 col-sm-4 text-center">
                <a href="/admin/reports/seasons">
                    <h2 class="semi-bold text-success no-margin p-t-35 p-b-10">{{ number_format($playerCount) }}</h2>
                    <div class="tiles-title blend p-b-25">PLAYERS</div>
                </a>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-4 col-sm-4 text-center">
                <h2 class="semi-bold text-warning no-margin p-t-35 p-b-10">
                    @if($averageGroupSize > 0)
                        {{ number_format($averageGroupSize) }}
                    @else
                        0
                    @endif
                </h2>
                <div class="tiles-title blend p-b-25">AVG GROUP SIZE</div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
