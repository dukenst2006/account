<?php
    // The following participant types are required to
    // register for all tournaments
    $participantsRequiredToRegister = [
        \BibleBowl\ParticipantType::PLAYER,
        \BibleBowl\ParticipantType::TEAM
    ];
?>

<div class="row">
    <div class="col-md-6 col-sm-6 form-group">
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
    <div class="col-md-6 col-sm-6 form-group">
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
    <div class="col-md-4 col-sm-4 col-xs-4 form-group">
        <label class="form-label">Maximum Teams <span class="required">*</span></label>
        <div class="controls p-b-20">
            {!! Form::text('max_teams', null, ['class' => 'form-control', 'maxlength' => 3]) !!}
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2"></div>
    <div class="col-md-6 col-sm-6 col-xs-6 form-group">
        <label class="form-label">Lock Teams</label>
        <span class="help">If provided, teams can only be changed by you after this date</span>
        <div class="controls p-b-10">
            <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) && $tournament->teamsWillLock() ? $tournament->lock_teams->format('m/d/Y') : '') }}">
                {!! Form::text('lock_teams', (isset($tournament) && $tournament->teamsWillLock() ? $tournament->lock_teams->format('m/d/Y') : null), ['class' => 'form-control']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <label class="form-label">Participants</label>
        <div class="help">Player and Team participants are assumed for all tournaments so they will automatically be required to register</div>
        <table class="table no-more-tables">
            <thead>
            <tr>
                <th style="width:50%"></th>
                <th style="width:30%">Require Registration <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Requires participants to register"></i></th>
                <th style="width:20%">Fee <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Charge specific participants fees to attend.  Required registration must be enabled"></i></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($participantTypes as $type)
                <tr>
                    <td>
                        {{ $type->name }}
                        <div class="help">{{ $type->description }}</div>
                    </td>
                    <td>
                        @if(!in_array($type->id, $participantsRequiredToRegister))
                        <div class="checkbox check-default">
                            {!! Form::checkbox("particpantTypes[".$type->id."][requireRegistration]", 1, old("particpantTypes[".$type->id."][requireRegistration]", isset($tournament) && $participantFees->has($type->id) && $participantFees->get($type->id)->requires_registration), [ "id" => 'particpantType'.$type->id.'requireRegistration' ]) !!}
                            <label for='particpantType{{ $type->id }}requireRegistration'></label>
                        </div>
                        @endif
                    </td>
                    <td>{!! Form::money('particpantTypes['.$type->id.'][fee]', old('particpantTypes['.$type->id.'][fee]', (isset($tournament) && $participantFees->has($type->id)) ? $participantFees->get($type->id)->fee : '0'), ['class' => 'form-control']) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
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