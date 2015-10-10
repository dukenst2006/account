<?php

use BibleBowl\Role;
use BibleBowl\Permission;
use Illuminate\Database\Migrations\Migration;

class AddRoles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
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
        $director->attachPermissions([$viewReports, $manageRoles]);
        $boardMember->attachPermissions([$viewReports]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Permission::where('name', Permission::MANAGE_CHILDREN)->delete();
		Role::where('id', '>', 0)->delete();
	}

}
