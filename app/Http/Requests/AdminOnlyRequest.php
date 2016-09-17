<?php namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Player;
use BibleBowl\Role;

class AdminOnlyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->is(Role::ADMIN);
    }
}