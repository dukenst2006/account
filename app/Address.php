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
     * @return bool
     */
    public function isOwnedByUser()
    {
        return !is_null($this->user_id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('BibleBowl\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function group()
    {
        return $this->hasMany('BibleBowl\Group');
    }

    public static function validationRules()
    {
        return [
            'name'			=> 'required|max:32',
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

    public function setNameAttribute ($attribute)
    {
        $this->attributes['name'] = ucwords(strtolower($attribute));
    }

    public function setAddressOneAttribute ($attribute)
    {
        $this->attributes['address_one'] = ucwords(strtolower($attribute));
    }

    public function setAddressTwoAttribute ($attribute)
    {
        $this->attributes['address_two'] = ucwords(strtolower($attribute));
    }

    public function setCityAttribute ($attribute)
    {
        $this->attributes['city'] = ucwords(strtolower($attribute));
    }

}
