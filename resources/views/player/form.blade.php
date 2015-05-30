@section('before-styles-end')
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker.min.css" rel="stylesheet" type="text/css"/>
@endsection
@section('includeJs')
    <script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
@endsection
@section('js')
    $(document).ready(function () {
        $('.input-append.date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    });
@endsection

<div class="row">
    <div class="col-md-12">
        <label class="form-label">Name</label>
        <span class="help"></span>
        <div class="controls row p-b-20">
            <div class="col-md-6">
                {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 32, 'autofocus']) !!}
            </div>
            <div class="col-md-6">
                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 32]) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label">T-Shirt Size</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::selectShirtSize('shirt_size', null, ['class' => 'form-control']) !!}<br/>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Gender</label>
        <span class="help"></span>
        <div class="controls">
            @include('partials.forms.gender')
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 p-b-20">
        <label class="form-label">Birthday</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            <div class="input-append success date col-md-10 col-lg-6 no-padding" data-date="{{ (isset($player) ? $player->birthday->format('m/d/Y') : \Carbon\Carbon::now()->subYears(14)->format('m/d/Y')) }}">
                {!! Form::text('birthday', (isset($player) ? $player->birthday->format('m/d/Y') : null), ['class' => 'form-control']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
    </div>
</div>