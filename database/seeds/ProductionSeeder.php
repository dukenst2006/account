<?php

use App\Ability;
use App\EventType;
use App\GroupType;
use App\ParticipantType;
use App\Player;
use App\Program;
use App\RegistrationSurveyAnswer;
use App\RegistrationSurveyQuestion;
use App\Role;
use App\Season;
use App\TournamentType;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Season::create([
            'name' => date('Y').'-'.(date('y') + 1),
        ]);
        Program::create([
            'name'              => 'Beginner Bible Bowl',
            'abbreviation'      => 'Beginner',
            'slug'              => 'beginner',
            'registration_fee'  => '25.00',
            'min_grade'         => 2,
            'max_grade'         => 5,
        ]);

        Program::create([
            'name'              => 'Teen Bible Bowl',
            'abbreviation'      => 'Teen',
            'slug'              => 'teen',
            'registration_fee'  => '35.00',
            'min_grade'         => 6,
            'max_grade'         => 12,
        ]);
        GroupType::create([
            'name' => 'Christian School',
        ]);
        GroupType::create([
                'name' => 'Homeschool',
            ]);
        GroupType::create([
                'name' => 'Church',
            ]);
        GroupType::create([
                'name' => 'Other',
            ]);

        ParticipantType::create([
            'name' => 'Team',
        ]);
        ParticipantType::create([
            'name' => 'Player',
        ]);
        ParticipantType::create([
            'name' => 'Quizmaster',
        ]);
        ParticipantType::create([
            'name'          => 'Spectator - Adult',
            'description'   => 'Single adult',
        ]);
        ParticipantType::create([
            'name'          => 'Spectator - Family',
            'description'   => 'Up to 2 adults and children who are not players',
        ]);

        EventType::create([
            'participant_type_id'   => ParticipantType::TEAM,
            'name'                  => 'Round Robin',
        ]);
        EventType::create([
            'participant_type_id'   => ParticipantType::PLAYER,
            'name'                  => 'Quote Bee',
        ]);
        EventType::create([
            'participant_type_id'   => ParticipantType::TEAM,
            'name'                  => 'Double Elimination',
        ]);
        EventType::create([
            'participant_type_id'   => ParticipantType::TEAM,
            'name'                  => 'Single Elimination',
        ]);
        EventType::create([
            'participant_type_id'   => ParticipantType::PLAYER,
            'name'                  => 'BuzzOff',
        ]);
        EventType::create([
            'participant_type_id'   => ParticipantType::PLAYER,
            'name'                  => 'King of the Hill',
        ]);
        EventType::create([
            'participant_type_id'   => ParticipantType::PLAYER,
            'name'                  => 'Written Test',
        ]);
        EventType::create([
            'participant_type_id'   => ParticipantType::PLAYER,
            'name'                  => 'Individual Tournament',
        ]);

        Bouncer::allow(Role::ADMIN)->to([
            Ability::VIEW_REPORTS,
            Ability::MANAGE_ROLES,
            Ability::MANAGE_USERS,
            Ability::MANAGE_GROUPS,
            Ability::MANAGE_PLAYERS,
            Ability::CREATE_TOURNAMENTS,
            Ability::SWITCH_ACCOUNTS,
            Ability::MANAGE_SETTINGS,
        ]);

        Bouncer::allow(Role::BOARD_MEMBER)->to(Ability::VIEW_REPORTS);

        Bouncer::allow(Role::HEAD_COACH)->to([
            Ability::MANAGE_ROSTER,
            Ability::MANAGE_TEAMS,
        ]);
        Role::create([
                'name'                  => Role::COACH,
                'mailchimp_interest_id' => '29a52dd6fc',
            ]);
        Role::create([
                'name'                  => Role::LEAGUE_COORDINATOR,
                'mailchimp_interest_id' => '9b90dc8bdd',
            ]);
        Role::create([
            'name'                  => Role::TOURNAMENT_COORDINATOR,
            'mailchimp_interest_id' => 'd6fc432290',
        ]);
        Role::create([
                'name'                  => Role::QUIZMASTER,
                'mailchimp_interest_id' => 'fe3a183033',
            ]);
        Bouncer::allow(Role::QUIZMASTER);
        Bouncer::allow(Role::GUARDIAN)->to(Ability::REGISTER_PLAYERS);
        Bouncer::allow(Role::TOURNAMENT_COORDINATOR)->to(Ability::CREATE_TOURNAMENTS);

        Role::where('name', Role::HEAD_COACH)->update([
            'mailchimp_interest_id' => 'be4c459134',
        ]);
        Role::where('name', Role::GUARDIAN)->update([
            'mailchimp_interest_id' => '0f83e0f312',
        ]);

        $howDidYouHearAbout = RegistrationSurveyQuestion::create([
            'question'  => 'How did you hear about Bible Bowl?',
            'order'     => 1,
        ]);
        $howDidYouHearAbout->answers()->saveMany([
            new RegistrationSurveyAnswer([
                'answer'    => 'Friend',
                'order'     => '1',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Church brochure/bulletin',
                'order'     => '2',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Homeschool convention',
                'order'     => '3',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'TV',
                'order'     => '4',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Web Advertisement',
                'order'     => '5',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Internet',
                'order'     => '6',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Other',
                'order'     => '7',
            ]),
        ]);

        $mostInfluential = RegistrationSurveyQuestion::create([
            'question'  => 'Which of the following were most influential in your decision to join Bible Bowl?',
            'order'     => 2,
        ]);
        $mostInfluential->answers()->saveMany([
            new RegistrationSurveyAnswer([
                'answer'    => "Friend's recommendation",
                'order'     => '1',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Attending a practice/demo/meeting',
                'order'     => '2',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Learning about it on the web site',
                'order'     => '3',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Homeschool curriculum potential',
                'order'     => '4',
            ]),
            new RegistrationSurveyAnswer([
                'answer'    => 'Other',
                'order'     => '5',
            ]),
        ]);

        TournamentType::create([
            'name' => 'National',
        ]);
        TournamentType::create([
            'name' => 'College',
        ]);
        TournamentType::create([
            'name' => 'Other',
        ]);
    }
}
