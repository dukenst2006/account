<?php

namespace App\Http\Requests;

use App\Player;
use Auth;

class PlayerGuardianOnlyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Player::where('id', $this->route('player'))->where('guardian_id', Auth::user()->id)->count() > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
