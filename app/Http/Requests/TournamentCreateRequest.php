<?php

namespace BibleBowl\Http\Requests;

use BibleBowl\Ability;
use BibleBowl\ParticipantType;
use Bouncer;

class TournamentCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Bouncer::allows(Ability::CREATE_TOURNAMENTS);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                                                                  => 'required',
            'start'                                                                 => 'required',
            'end'                                                                   => 'required',
            'registration_start'                                                    => 'required',
            'registration_end'                                                      => 'required',
            'max_teams'                                                             => 'required',
            'minimum_players_per_team'                                              => 'required',
            'maximum_players_per_team'                                              => 'required',

            'participantTypes.'.ParticipantType::QUIZMASTER.'.requireRegistration'  => 'required_unless:require_quizmasters_per,none'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'start.required'                                                                        => 'A start date is required',
            'end.required'                                                                          => 'An end date is required',
            'registration_start.required'                                                           => 'A registration start date is required',
            'registration_end.required'                                                             => 'A registration end date is required',
            'max_teams.required'                                                                    => 'Max number of teams is required',
            'minimum_players_per_team.required'                                                     => 'Minimum number of players per team is required',
            'maximum_players_per_team.required'                                                     => 'Maximum number of players per team is required',
            'participantTypes.'.ParticipantType::QUIZMASTER.'.requireRegistration.required_unless'  => 'Quizmaster registration must be enabled in order to require quizmasters by groups',
        ];
    }
}
