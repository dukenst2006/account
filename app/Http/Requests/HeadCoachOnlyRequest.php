<?php

namespace App\Http\Requests;

use App\Role;
use Auth;

class HeadCoachOnlyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAn(Role::HEAD_COACH);
    }
}
