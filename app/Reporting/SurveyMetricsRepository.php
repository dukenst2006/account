<?php namespace BibleBowl\Reporting;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\UserSurveyQuestion;
use DB;

class SurveyMetricsRepository
{
    public function byQuestion(UserSurveyQuestion $question, Season $season)
    {
        return [
            $question->surveys()
                ->select(DB::raw('count(user_surveys.id) as total'))
                ->groupBy('answer_id')
                ->whereBetween('user_surveys.created_at', [$season->start(), $season->end()])
                ->get()->toArray()
        ];
    }
}
