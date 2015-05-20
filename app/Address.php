<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('BibleBowl\User');
    }

    public static function validationRules()
    {
        return [
            'name'			=> 'required|max:32',
            'first_name'	=> 'required|max:32',
            'last_name'		=> 'required|max:32',
            'address_one'	=> 'required|max:255',
            'address_two'	=> 'max:255',
            'city'			=> 'required|min:3|max:255',
            'state'			=> 'required',
            'zip_code'		=> 'required'
        ];
    }

    public static function validationMessages()
    {
        return [
            'address_one.required' => 'Street address is required'
        ];
    }

}
