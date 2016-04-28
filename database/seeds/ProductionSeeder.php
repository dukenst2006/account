<?php

use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Role;
use BibleBowl\Ability;
use BibleBowl\User;
use BibleBowl\Group;
use BibleBowl\EventType;
use Illuminate\Database\Seeder;
use BibleBowl\Program;
use BibleBowl\OrderStatus;

class ProductionSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        Program::create([
            'name'              => 'Beginner Bible Bowl',
            'abbreviation'      => 'Beginner',
            'slug'              => 'beginner',
            'registration_fee'  => '25.00',
            'min_grade'         => 3,
            'max_grade'         => 5
        ]);

        Program::create([
            'name'              => 'Teen Bible Bowl',
            'abbreviation'      => 'Teen',
            'slug'              => 'teen',
            'registration_fee'  => '35.00',
            'min_grade'         => 6,
            'max_grade'         => 12
        ]);

        EventType::create([
            'participant_type'  => EventType::PARTICIPANT_TEAM,
            'name'              => 'Round Robin'
        ]);
        EventType::create([
            'participant_type'  => EventType::PARTICIPANT_PLAYER,
            'name'              => 'Quote Bee'
        ]);
        EventType::create([
            'participant_type'  => EventType::PARTICIPANT_TEAM,
            'name'              => 'Double Elimination'
        ]);
        EventType::create([
            'participant_type'  => EventType::PARTICIPANT_PLAYER,
            'name'              => 'BuzzOff'
        ]);
        Bouncer::allow(Role::ADMIN)->to([
            Ability::VIEW_REPORTS,
            Ability::MANAGE_ROLES,
            Ability::MANAGE_USERS,
            Ability::MANAGE_GROUPS,
            Ability::MANAGE_PLAYERS,
            Ability::CREATE_TOURNAMENTS,
            Ability::SWITCH_ACCOUNTS,
            Ability::MANAGE_SETTINGS
        ]);

        Bouncer::allow(Role::BOARD_MEMBER)->to(Ability::VIEW_REPORTS);

        Bouncer::allow(Role::HEAD_COACH)->to([
            Ability::MANAGE_ROSTER,
            Ability::MANAGE_TEAMS
        ]);
            Role::create([
                'name'                  => Role::COACH,
                'mailchimp_interest_id' => 'd531b08cdb'
            ]);
            Role::create([
                'name'                  => Role::LEAGUE_COORDINATOR,
                'mailchimp_interest_id' => 'da431848e5'
            ]);
            Role::create([
                'name'                  => Role::QUIZMASTER,
                'mailchimp_interest_id' => 'bddc8cb120'
            ]);
        Bouncer::allow(Role::QUIZMASTER);
        Bouncer::allow(Role::GUARDIAN)->to(Ability::REGISTER_PLAYERS);

        Role::where('name', Role::HEAD_COACH)->update([
            'mailchimp_interest_id' => '8eb76f09f0'
        ]);
        Role::where('name', Role::GUARDIAN)->update([
            'mailchimp_interest_id' => 'f29d2ce1ef'
        ]);
    }

}
