<?php

use BibleBowl\User;
use BibleBowl\Role;

class UsersTest extends TestCase
{

    protected $user;

    use \Helpers\ActingAsDirector;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /**
     * @test
     */
    public function searchUserList()
    {
        $this
            ->visit('/admin/users')
            ->see('Ben Guardian')
            ->visit('/admin/groups?q=Josiah')
            ->dontSee('Ben Guardian');
    }

    /**
     * @test
     */
    public function viewUser()
    {
        $user = User::first();

        $this
            ->visit('/admin/users/'.$user->id)
            ->see($user->full_name);
    }

    /**
     * @test
     */
    public function canEditRoles()
    {
        $guardian = User::whereEmail(DatabaseSeeder::GUARDIAN_EMAIL)->firstOrFail();
        $role = Role::where('name', Role::ADMIN)->firstOrFail();

        $this
            ->visit('/admin/users/'.$guardian->id)
            ->click('(add/remove)')
            ->check('role['.$role->id.']')
            ->press('Save')
            ->see('Your changes were saved')
            ->click('(add/remove)')
            ->uncheck('role['.$role->id.']')
            ->press('Save');
    }

    /**
     * @test
     */
    public function adminsCanSwitchUsers()
    {
        $guardian = User::whereEmail(DatabaseSeeder::GUARDIAN_EMAIL)->firstOrFail();

        $this
            ->visit('/dashboard')
            ->visit('/admin/switchUser/'.$guardian->id)
            ->assertEquals($guardian->email, $this->app['auth.driver']->user()->email);

        $this
            ->visit('/logout')
            ->assertEquals($this->director()->email, $this->app['auth.driver']->user()->email);
    }
}
