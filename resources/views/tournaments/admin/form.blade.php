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
    <div class="col-md-12 form-group">
        <label class="form-label">Participants</label>
        <div class="help">Player and Team participants are assumed for all tournaments so they will automatically be required to register</div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th style="width:30%"></th>
                    <th style="width:15%">Registration Required <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enables registration for participants"></i></th>
                    <th style="width:20%">Early Bird <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Allows for lower fees for those that register before a given date. If no date is set, early bird fees will be ignored"></i></th>
                    <th style="width:15%">Fee</th>
                    <th style="width:15%">On-site Fee <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="On-site is an offline registration and will not use this web site"></i></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) && $tournament->hasEarlyBirdRegistration() ? $tournament->earlybird_ends->format('m/d/Y') : '') }}">
                            {!! Form::text('earlybird_ends', old('earlybird_ends', (isset($tournament) && $tournament->hasEarlyBirdRegistration() ? $tournament->earlybird_ends->format('m/d/Y') : null)), ['class' => 'form-control']) !!}
                            <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($participantTypes as $type)
                    <tr>
                        <td>
                            {{ $type->name }}
                            <div class="help">{{ $type->description }}</div>
                        </td>
                        <td>
                            @if(!in_array($type->id, \BibleBowl\Tournament::PARTICIPANTS_REQUIRED_TO_REGISTER))
                                <div class="checkbox check-default">
                                    {!! Form::checkbox("participantTypes[".$type->id."][requireRegistration]", 1, old("participantTypes[".$type->id."][requireRegistration]", isset($tournament) && $participantFees->has($type->id) && $participantFees->get($type->id)->requires_registration), [ "id" => 'participantType'.$type->id.'requireRegistration' ]) !!}
                                    <label for='participantType{{ $type->id }}requireRegistration'></label>
                                </div>
                            @endif
                        </td>
                        <td>{!! Form::money('participantTypes['.$type->id.'][earlybird_fee]', old('participantTypes['.$type->id.'][earlybird_fee]', (isset($tournament) && $participantFees->has($type->id)) ? $participantFees->get($type->id)->earlybird_fee : '0'), ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::money('participantTypes['.$type->id.'][fee]', old('participantTypes['.$type->id.'][fee]', (isset($tournament) && $participantFees->has($type->id)) ? $participantFees->get($type->id)->fee : '0'), ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::money('participantTypes['.$type->id.'][onsite_fee]', old('participantTypes['.$type->id.'][onsite_fee]', (isset($tournament) && $participantFees->has($type->id)) ? $participantFees->get($type->id)->onsite_fee : '0'), ['class' => 'form-control']) !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-6 form-group">
        <label class="form-label">Maximum Teams <span class="required">*</span></label>
        <div class="controls p-b-20">
            {!! Form::text('max_teams', null, ['class' => 'form-control', 'maxlength' => 3, 'style' => 'width: 100px']) !!}
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-6 form-group">
        <label class="form-label">Lock Teams</label>
        <span class="help">If provided, teams can only be changed by you after this date</span>
        <div class="controls p-b-10">
            <div class="input-append success date" style='width:100px' data-date="{{ (isset($tournament) && $tournament->teamsWillLock() ? $tournament->lock_teams->format('m/d/Y') : '') }}">
                {!! Form::text('lock_teams', (isset($tournament) && $tournament->teamsWillLock() ? $tournament->lock_teams->format('m/d/Y') : null), ['class' => 'form-control']) !!}
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-3 col-sm-3 col-xs-12 form-group m-l-10">
        <label class="form-label">Players per team <span class="required">*</span></label>
        <div class="controls p-b-20">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="help">Minimum</div>
                    {!! Form::text('minimum_players_per_team', old('minimum_players_per_team', (isset($tournament) ? $tournament->settings->minimumPlayersPerTeam() : \BibleBowl\Competition\Tournaments\Settings::DEFAULT_MINIMUM_PLAYERS_PER_TEAM)), ['class' => 'form-control', 'maxlength' => 1]) !!}
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="help">Maximum</div>
                    {!! Form::text('maximum_players_per_team', old('maximum_players_per_team', (isset($tournament) ? $tournament->settings->maximumPlayersPerTeam() : \BibleBowl\Competition\Tournaments\Settings::DEFAULT_MAXIMUM_PLAYERS_PER_TEAM)), ['class' => 'form-control', 'maxlength' => 1]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <label class="form-label">Requiring Quizmasters</label>
        <div class="controls p-b-20 p-l-20">
            <div class="radio">
                {!! Form::radio('require_quizmasters_per', 'none', old('require_quizmasters_per', (isset($tournament) ? $tournament->settings->quizmasterRequirement() : true)), ['id' => 'requireQuizmastersPerNothing']) !!}
                <label for="requireQuizmastersPerNothing"><strong>Don't require quizmasters</strong> - Quizmasters can still register (if enabled) but don't contribute to group/team eligibility</label>
            </div>
            <br/>
            <div class="radio radio-primary radio-with-form-fields">
                {!! Form::radio('require_quizmasters_per', 'group', old('require_quizmasters_per', (isset($tournament) ? $tournament->settings->shouldRequireQuizmastersByGroup() : false)), ['id' => 'requireQuizmastersPerGroup']) !!}
                <label for="requireQuizmastersPerGroup"><strong>Require {!! Form::selectRange('quizmasters_per_group', 1, 5, (isset($tournament) ? $tournament->settings->quizmastersToRequireByGroup() : old('quizmasters_per_group')), ['style' => 'width: 40px']) !!} quizmaster(s) per group</strong></label>
            </div>
            <br/>
            <div class="radio radio-primary radio-with-form-fields">
                {!! Form::radio('require_quizmasters_per', 'team_count', old('require_quizmasters_per', (isset($tournament) ? $tournament->settings->shouldRequireQuizmastersByTeamCount() : false)), ['id' => 'requireQuizmastersPerTeamCount']) !!}
                <label for="requireQuizmastersPerTeamCount"><strong>Require
                        {!! Form::selectRange('quizmasters_per_team_count', 1, 5, (isset($tournament) ? $tournament->settings->quizmastersToRequireByTeamCount() : old('quizmasters_per_team_count')), ['style' => 'width: 40px']) !!}
                        quizmaster(s) per group's
                        {!! Form::selectRange('quizmasters_team_count', 1, 5, (isset($tournament) ? $tournament->settings->teamCountToRequireQuizmastersBy() : old('quizmasters_team_count', 2)), ['style' => 'width: 40px']) !!}
                        team(s)</strong></label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <label class="form-label">Other Options</label>
        <div class="controls p-b-20 p-l-20">
            <div class="checkbox check-primary">
                {!! Form::checkbox("collect_shirt_sizes", 1, old("collect_shirt_sizes", (isset($tournament) ? $tournament->settings->shouldCollectShirtSizes() : true)), [ "id" => 'collectShirtSizes' ]) !!}
                <label for="collectShirtSizes"><strong>Collect T-Shirt Sizes</strong> - Collects t-shirt sizes for all participants who register online</label>
            </div>
            <br/>
            <div class="checkbox check-primary">
                {!! Form::checkbox("collect_quizmaster_preferences", 1, old("collect_quizmaster_preferences", (isset($tournament) ? $tournament->settings->shouldCollectQuizmasterPreferences() : false)), [ "id" => 'collectQuizmasterPreferences' ]) !!}
                <label for="collectQuizmasterPreferences"><strong>Collect Quizzing Preferences</strong> - Allows quizmasters to provide information on quizzing interest/history</label>
            </div>
            <br/>
            <div class="checkbox check-primary">
                {!! Form::checkbox("allow_guest_players", 1, old("allow_guest_players", (isset($tournament) ? $tournament->settings->shouldAllowGuestPlayers() : false)), [ "id" => 'allowsGuestPlayers' ]) !!}
                <label for="allowsGuestPlayers"><strong>Allow Guest Players</strong> - Allows players from groups not participating in the tournament to play with groups that are</label>
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