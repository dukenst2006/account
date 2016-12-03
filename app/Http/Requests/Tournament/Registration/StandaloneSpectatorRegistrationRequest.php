<?php

namespace BibleBowl\Http\Requests\Tournament\Registration;

use BibleBowl\Address;
use BibleBowl\Http\Requests\Request;
use BibleBowl\Spectator;
use BibleBowl\Tournament;
use Illuminate\Database\Eloquent\Builder;
use Validator;

class StandaloneSpectatorRegistrationRequest extends Request
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
        $tournament = Tournament::where('slug', $this->route('slug'))->firstOrFail();
        $this->tournament = $tournament;

        // Prevent multiple spectators from registering using the same email
        Validator::extend('spectator_hasnt_registered', function ($attribute, $value, $parameters, $validator) use ($tournament) {
            return Spectator::where('tournament_id', $this->tournament->id)->where('email', $value)->count() == 0 &&
            Spectator::where('tournament_id', $this->tournament->id)->whereHas('user', function (Builder $q) use ($value) {
                $q->where('email', $value);
            })->count() == 0;
        });

        $rules = [
            'spouse_gender'         => 'required_with:spouse_first_name',

            'minor.*.age'           => 'required_with:minor.*.age',
            'minor.*.gender'        => 'required_with:minor.*.gender',

            'first_name'            => 'required_unless:registering_as_current_user,1|max:32',
            'last_name'             => 'required_unless:registering_as_current_user,1|max:32',
            'email'                 => 'required_unless:registering_as_current_user,1|email|max:128|spectator_hasnt_registered',
            'gender'                => 'required_unless:registering_as_current_user,1',
        ];

        if ($tournament->settings->shouldCollectShirtSizes()) {
            $rules['shirt_size'] = 'required';
            $rules['spouse_shirt_size'] = 'required_with:spouse_first_name';
            $rules['minor.*.shirt_size'] = 'required_with:minor.*.first_name';
        }

        // add address validation rules with a condition
        $addressRules = array_except(Address::validationRules(), ['name']);
        foreach ($addressRules as $field => $rule) {
            $rules[$field] = str_replace('required', 'required_unless:registering_as_current_user,1', $rule);
        }

        return $rules;
    }

    public function messages()
    {
        return array_merge([
            'first_name.required_unless'        => 'The first name field is required',
            'last_name.required_unless'         => 'The last name field is required',
            'email.required_unless'             => 'The email field is required',
            'gender.required_unless'            => 'The gender field is required',
            'shirt_size.required'               => 'The tshirt size is required',
            'email.spectator_hasnt_registered'  => 'A spectator with this email address has already been registered',

            'address_one.required_unless'   => 'The street address field is required',
            'zip_code.required_unless'      => 'The zip code field is required',

            'spouse_gender.required'        => "Your spouse's gender is required",
            'spouse_shirt_size.required'    => "Your spouse's shirt size is required",
        ], Address::validationMessages());
    }
}
