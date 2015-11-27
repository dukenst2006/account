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

}