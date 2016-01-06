<?php

use BibleBowl\User;

class UsersTest extends TestCase
{

    protected $user;

    use \Helpers\ActingAsDirector;

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
    public function adminsCanSwitchUsers()
    {
        $guardian = User::whereEmail(DatabaseSeeder::GUARDIAN_EMAIL)->first();

        $this
            ->visit('/dashboard')
            ->visit('/admin/switchUser/'.$guardian->id)
            ->assertEquals($guardian->email, $this->app['auth.driver']->user()->email);

        $this
            ->visit('/logout')
            ->assertEquals($this->director()->email, $this->app['auth.driver']->user()->email);
    }

}