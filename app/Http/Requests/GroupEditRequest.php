<?php

namespace App\Http\Requests;

use App\Group;

class GroupEditRequest extends GroupCreatorOnlyRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $isEditingExistingGroup = $this->route('group') > 0;

        return array_except(
            Group::validationRules($isEditingExistingGroup),
            [
                'program_id',
                'owner_id',
            ]
        );
    }
}
