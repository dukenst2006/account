<?php

namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Player;

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
