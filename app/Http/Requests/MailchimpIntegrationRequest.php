<?php

namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Group;
use BibleBowl\Role;
use Easychimp\Easychimp;
use Easychimp\InvalidApiKey;
use Mailchimp\Mailchimp;
use Session;
use Validator;

class MailchimpIntegrationRequest extends GroupCreatorOnlyRequest
{
    /** @var Easychimp */
    public static $easychimp;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $groupId = $this->route('group');
        if (Auth::user()->isA(Role::HEAD_COACH)) {
            $groupId = Session::group()->id;
        }

        return Group::where('id', $groupId)
            ->where('owner_id', Auth::id())
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // validate that the provided API key is accurate
        Validator::extend('valid_mailchimp_key', function ($attr, $val) {

            // maintain running tests offline
            if (app()->environment('testing')) {
                return true;
            }

            $easychimp = new Easychimp(new Mailchimp($val));
            try {
                $easychimp->validateKey();

                // we'll reuse this when validating the list id
                MailchimpIntegrationRequest::$easychimp = $easychimp;
            } catch (InvalidApiKey $e) {
                return false;
            }

            return true;
        });

        // validate that the provided list id is accurate
        Validator::extend('valid_mailchimp_list_id', function ($attr, $val) {

            // maintain running tests offline
            if (app()->environment('testing')) {
                return true;
            }

            return MailchimpIntegrationRequest::$easychimp->mailingList($val)->exists();
        });

        return [
            'mailchimp-key'     => 'required_if:mailchimp-enabled,1|valid_mailchimp_key',
            'mailchimp-list-id' => 'required_with:mailchimp-enabled|valid_mailchimp_list_id',
        ];
    }

    public function messages()
    {
        return [
            'mailchimp-key.required_if'                 => 'An API key is required to enable Mailchimp integration',
            'mailchimp-key.valid_mailchimp_key'         => 'API key is invalid',
            'mailchimp-list-id.required_with'           => 'A mailing list ID is required',
            'mailchimp-list-id.valid_mailchimp_list_id' => 'Mailing list ID is invalid',
        ];
    }
}
