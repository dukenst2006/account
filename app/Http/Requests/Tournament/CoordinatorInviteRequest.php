<?php

namespace App\Http\Requests\Tournament;

use App\Group;
use App\Http\Requests\Request;
use App\Tournament;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class CoordinatorInviteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Tournament::whereHas('coordinators', function (Builder $q) {
                $q->where('id', Auth::user()->id);
            })->where('id', $this->route('tournament'))->count() > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
