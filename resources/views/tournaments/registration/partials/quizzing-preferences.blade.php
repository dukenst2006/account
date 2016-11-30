<div class="row form-group">
    <div class="col-md-6 col-sm-6 col-xs-6">
        <label class="form-label">Have you quizzed at this Tournament before?</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            <div class="radio">
                {!! Form::radio('quizzed_at_tournament', '0', old('quizzed_at_tournament', (int)$quizzingPreferences->quizzedAtThisTournamentBefore()) == 0, ['id' => 'quizzed_at_tournament_no']) !!}
                {!! Form::label('quizzed_at_tournament_no', 'No') !!}
                {!! Form::radio('quizzed_at_tournament', '1', old('quizzed_at_tournament', (int)$quizzingPreferences->quizzedAtThisTournamentBefore()) == 1, ['id' => 'quizzed_at_tournament_yes']) !!}
                {!! Form::label('quizzed_at_tournament_yes', 'Yes') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
        <label class="form-label">If yes, how many times?</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::select('times_quizzed_at_tournament', [
                '' => '',
                '1-3' => '1-3',
                '4-6' => '4-6',
                '7-9' => '7-9',
                '10+' => '10+'
            ], old('times_quizzed_at_tournament', $quizzingPreferences->timesQuizzedAtThisTournament()), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <label class="form-label">How many games have you quizzed this season?</label>
        <span class="help"></span>
        <div class="controls p-b-20">
            {!! Form::select('games_quizzed_this_season', [
                'Just a few'        => 'Just a few',
                'Fewer than 15'     => 'Fewer than 15',
                'Fewer than 30'     => 'Fewer than 30',
                'More than 30'      => 'More than 30'
            ], old('games_quizzed_this_season', $quizzingPreferences->gamesQuizzedThisSeason()), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <label class="form-label">What's your interest in quizzing?</label>
        <span class="help">On a scale of 1-5</span>
        <div class="controls p-b-20">
            {!! Form::select('quizzing_interest', [
                '1' => '1 - Only if you really need me',
                '2' => '2 - Whatever you need',
                '3' => "3 - I prefer quizzing over watching"
            ], old('quizzing_interest', $quizzingPreferences->quizzingInterest()), ['class' => 'form-control']) !!}
        </div>
    </div>
</div>