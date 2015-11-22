<?php

use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Role;
use BibleBowl\Permission;
use BibleBowl\User;
use BibleBowl\Group;
use BibleBowl\EventType;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        EventType::create([
            'name'          => 'Round Robin'
        ]);
        EventType::create([
            'name'          => 'Quote Bee'
        ]);
        EventType::create([
            'name'          => 'Double Elimination'
        ]);
        EventType::create([
            'name'          => 'BuzzOff'
        ]);

        $this->createRolesAndPermissions();
    }

    private function createRolesAndPermissions()
    {
        $director = Role::create([
            'name'			=> Role::DIRECTOR,
            'display_name' 	=> 'National Director',
        ]);
        Role::create([
            'name'			=> Role::HEAD_COACH,
            'display_name' 	=> 'Head Coach',
        ]);
        $boardMember = Role::create([
            'name'			=> Role::BOARD_MEMBER,
            'display_name' 	=> 'Board Member',
        ]);
        Role::create([
            'name'			=> Role::RR_COORDINATOR,
            'display_name' 	=> 'Round Robin Coordinator',
        ]);
        Role::create([
            'name'			=> Role::QUIZMASTER,
            'display_name' 	=> 'Quizmaster',
        ]);
        Role::create([
            'name'			=> Role::COACH,
            'display_name' 	=> 'Coach',
        ]);
        Role::create([
            'name'			=> Role::GUARDIAN,
            'display_name' 	=> 'Parent/Guardian',
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
        $director->attachPermissions([$viewReports, $manageRoles, $createTournaments]);
        $boardMember->attachPermissions([$viewReports]);
    }

}
