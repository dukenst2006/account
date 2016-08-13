@includeDatePicker
@js
    $(document).ready(function () {
        $('.input-append.date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    });
@endjs

<div class="row">
    <div class="col-md-12">
        <label class="form-label">Name</label>
        <span class="help"></span>
        <div class="controls row p-b-20">
            <div class="col-md-6 col-sm-6 col-xs-6">
                {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First', 'maxlength' => 32, 'autofocus']) !!}
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last', 'maxlength' => 32]) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
        <label class="form-label">Gender</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            @include('partials.forms.gender')
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6 p-b-20">
        <label class="form-label">Birthday</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            @if(!isset($player) || $player->isBirthdayEditable(Auth::user()))
                <div class="input-append success date col-md-10 col-lg-6 no-padding" data-date="{{ (isset($player) ? $player->birthday->format('m/d/Y') : \Carbon\Carbon::now()->subYears(14)->format('m/d/Y')) }}">
                    {!! Form::text('birthday', (isset($player) ? $player->birthday->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'Use date picker', (!isset($player) || $player->isBirthdayEditable(Auth::user()) ? 'readonly' : null)]) !!}
                    <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                </div>
            @else
                {!! Form::text('birthday', (isset($player) ? $player->birthday->format('m/d/Y') : null), [
                    'class' => 'form-control',
                    'data-toggle' => "tooltip",
                    'title' => "For eligibility reasons, you can no longer edit this player's birthday.",
                    'readonly' => 'readonly'
                ]) !!}

            @endif
        </div>
    </div>
</div>