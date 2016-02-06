<?php

use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Role;
use BibleBowl\Permission;
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
            'description'       => 'Grades 3 - 5'
        ]);

        Program::create([
            'name'              => 'Teen Bible Bowl',
            'abbreviation'      => 'Teen',
            'slug'              => 'teen',
            'registration_fee'  => '35.00',
            'description'       => 'Grades 6 - 12'
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

        $this->createRolesAndPermissions();
    }

    private function createRolesAndPermissions()
    {
        $director = Role::create([
            'name'			=> Role::DIRECTOR,
            'display_name' 	=> 'National Director',
        ]);
        $boardMember = Role::create([
            'name'			=> Role::BOARD_MEMBER,
            'display_name' 	=> 'Board Member',
        ]);
        Role::create([
            'name'			        => Role::LEAGUE_COORDINATOR,
            'display_name' 	        => 'League Coordinator',
            'mailchimp_interest_id' => 'f02726e8a2'
        ]);
        Role::create([
            'name'			        => Role::HEAD_COACH,
            'display_name' 	        => 'Head Coach',
            'mailchimp_interest_id' => '3aead42125'
        ]);
        Role::create([
            'name'			        => Role::COACH,
            'display_name' 	        => 'Coach',
            'mailchimp_interest_id' => '133cdf6794'
        ]);
        Role::create([
            'name'			        => Role::QUIZMASTER,
            'display_name' 	        => 'Quizmaster',
            'mailchimp_interest_id' => 'beed6e2cb0'
        ]);
        Role::create([
            'name'			        => Role::GUARDIAN,
            'display_name' 	        => 'Parent/Guardian',
            'mailchimp_interest_id' => '999d70a260'
        ]);
        $admin = Role::create([
            'name'			=> Role::ADMIN,
            'display_name' 	=> 'Admin',
        ]);

        $viewReports = Permission::create([
            'name'			=> Permission::VIEW_REPORTS,
            'display_name'	=> 'View Reports'
        ]);
        $manageRoles = Permission::create([
            'name'			=> Permission::MANAGE_ROLES,
            'display_name'	=> 'Manage User Roles'
        ]);
        $createTournaments = Permission::create([
            'name'			=> Permission::CREATE_TOURNAMENTS,
            'display_name'	=> 'Create Tournaments'
        ]);
        $switchAccounts = Permission::create([
            'name'			=> Permission::SWITCH_ACCOUNTS,
            'display_name'	=> 'Switch Accounts'
        ]);
        $settings = Permission::create([
            'name'			=> Permission::MANAGE_SETTINGS,
            'display_name'	=> 'Manage Settings'
        ]);
        $admin->attachPermissions([$viewReports, $manageRoles, $createTournaments, $switchAccounts, $settings]);
        $director->attachPermissions([$viewReports, $manageRoles, $createTournaments, $settings]);
        $boardMember->attachPermissions([$viewReports]);
    }

}
