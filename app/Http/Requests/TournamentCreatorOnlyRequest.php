<?php

namespace App\Http\Requests;

use App\Tournament;
use Auth;

class TournamentCreatorOnlyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Tournament::where('id', $this->route('tournament'))
            ->where('creator_id', Auth::id())
            ->exists();
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
