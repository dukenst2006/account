<?php namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Tournament;

class TournamentEditRequest extends TournamentCreateRequest
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
            ->exists() === false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'inactive' => 'required'
        ]);
    }
}
