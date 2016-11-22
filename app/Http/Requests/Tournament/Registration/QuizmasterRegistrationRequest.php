<?php

namespace BibleBowl\Http\Requests\Tournament\Registration;

use BibleBowl\Http\Requests\Request;
use BibleBowl\Tournament;

class QuizmasterRegistrationRequest extends Request
{
    /** @var Tournament */
    protected $tournament;

    public function tournament() : Tournament
    {
        return $this->tournament;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->tournament = Tournament::where('slug', $this->route('slug'))->first();

        $rules = [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email|quizmaster_not_registered:'.$this->tournament()->id,
            'phone'         => 'required|int',
        ];

        if ($this->tournament->settings->shouldCollectShirtSizes()) {
            $rules['shirt_size'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'first_name.required'               => 'First name is required',
            'last_name.required'                => 'Last name is required',
            'email.required'                    => 'Email address is required',
            'email.email'                       => 'Must be a valid email address',
            'phone.required'                    => 'Phone is required',
            'shirt_size.required'               => 'The tshirt size is required',
            'email.quizmaster_not_registered'   => 'This quizmaster is already registered for this tournament',
        ];
    }
}
