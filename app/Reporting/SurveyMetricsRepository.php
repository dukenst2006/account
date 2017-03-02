<?php

namespace App\Reporting;

use App\RegistrationSurveyQuestion;
use App\Season;
use DB;

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
