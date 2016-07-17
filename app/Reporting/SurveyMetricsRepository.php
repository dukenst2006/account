<?php namespace BibleBowl\Reporting;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\RegistrationSurveyQuestion;
use DB;
use Illuminate\Support\Collection;

class SurveyMetricsRepository
{
    public function byQuestion(RegistrationSurveyQuestion $question, Season $season) : array
    {
        return $question->surveys()
                ->select(
                    DB::raw('count(registration_surveys.id) as total'),
                    'answer'
                )
                ->groupBy('answer_id')
                ->whereBetween('registration_surveys.created_at', [$season->start(), $season->end()])
                ->get()->toArray();
    }
}
