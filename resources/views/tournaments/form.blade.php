<div class="row">
    <div class="col-md-6 form-group">
        <label class="form-label">Tournament Dates <span class="required">*</span></label>
        <div class="controls p-b-20">
            <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->start->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                {!! Form::text('start', (isset($tournament) ? $tournament->start->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'Start']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div> -
            <div class="input-append success date m-l-50" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->end->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                {!! Form::text('end', (isset($tournament) ? $tournament->end->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'End']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-6 form-group">
        <label class="form-label">Registration Dates <span class="required">*</span></label>
        <div class="controls p-b-20">
            <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->registration_start->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                {!! Form::text('registration_start', (isset($tournament) ? $tournament->registration_start->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'Start']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div> -
            <div class="input-append success date m-l-50" style='width:100px' data-date="{{ (isset($tournament) ? $tournament->registration_end->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')) }}">
                {!! Form::text('registration_end', (isset($tournament) ? $tournament->registration_end->format('m/d/Y') : null), ['class' => 'form-control', 'placeholder' => 'End']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <label class="form-label">Details</label>
        {!! Form::textarea('details', old('details', (isset($tournament) ? $tournament->details : '')), ['id' => 'text-editor', 'style' => 'width: 100%']) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-7 form-group">
        <label class="form-label">Maximum Teams <span class="required">*</span></label>
        <span class="help">A waiting list will be automatically formed when this number is reached</span>
        <div class="controls">
            {!! Form::text('max_teams', null, ['class' => 'form-control', 'maxlength' => 3]) !!}
        </div>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-4 form-group">
        <label class="form-label">Lock Teams</label>
        <span class="help">If provided, teams cannot be changed after this date</span>
        <div class="controls p-b-10">
            <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) && $tournament->teamsWillLock() ? $tournament->lock_teams->format('m/d/Y') : '') }}">
                {!! Form::text('lock_teams', (isset($tournament) && $tournament->teamsWillLock() ? $tournament->lock_teams->format('m/d/Y') : null), ['class' => 'form-control']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
    </div>
</div>

@includeRichTextEditor
@includeDatePicker
@js
    $(document).ready(function () {
        $('#text-editor').wysihtml5({
            stylesheets: ["/assets/plugins/bootstrap-wysihtml5/wysiwyg-color.css"],
        });

        $('.input-append.date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    });
@endjs