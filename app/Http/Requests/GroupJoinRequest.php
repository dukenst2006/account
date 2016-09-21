<?php

namespace BibleBowl\Http\Requests;

class GroupJoinRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'player' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'player.required' => 'You must select at least one player',
        ];
    }
}
