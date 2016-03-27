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

        Bouncer::allow(Role::DIRECTOR)->to(Ability::VIEW_REPORTS);
        Bouncer::allow(Role::DIRECTOR)->to(Ability::MANAGE_ROLES);
        Bouncer::allow(Role::DIRECTOR)->to(Ability::CREATE_TOURNAMENTS);
        Bouncer::allow(Role::DIRECTOR)->to(Ability::MANAGE_SETTINGS);

        Bouncer::allow(Role::BOARD_MEMBER)->to(Ability::VIEW_REPORTS);

        //@todo change these to ::create
        Bouncer::allow(Role::LEAGUE_COORDINATOR);
        Bouncer::allow(Role::HEAD_COACH);
        Bouncer::allow(Role::COACH);
        Bouncer::allow(Role::QUIZMASTER);
        Bouncer::allow(Role::GUARDIAN);

        Bouncer::allow(Role::ADMIN)->to(Ability::VIEW_REPORTS);
        Bouncer::allow(Role::ADMIN)->to(Ability::MANAGE_ROLES);
        Bouncer::allow(Role::ADMIN)->to(Ability::CREATE_TOURNAMENTS);
        Bouncer::allow(Role::ADMIN)->to(Ability::SWITCH_ACCOUNTS);
        Bouncer::allow(Role::ADMIN)->to(Ability::MANAGE_SETTINGS);

        Role::where('name', Role::LEAGUE_COORDINATOR)->update([
            'mailchimp_interest_id' => 'da431848e5'
        ]);
        Role::where('name', Role::HEAD_COACH)->update([
            'mailchimp_interest_id' => '8eb76f09f0'
        ]);
        Role::where('name', Role::COACH)->update([
            'mailchimp_interest_id' => 'd531b08cdb'
        ]);
        Role::where('name', Role::QUIZMASTER)->update([
            'mailchimp_interest_id' => 'bddc8cb120'
        ]);
        Role::where('name', Role::GUARDIAN)->update([
            'mailchimp_interest_id' => 'f29d2ce1ef'
        ]);
    }

}
