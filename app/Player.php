<?php namespace BibleBowl;

use Config;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;

class Player extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'guid'];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each user
        static::creating(function ($player) {
            $player->guid = uniqid();
            return true;
        });
    }

    public static function validationRules()
    {
        return [
            'first_name'	=> 'required|max:32',
            'last_name'		=> 'required|max:32',
            'shirt_size'	=> 'required',
            'gender'		=> 'required',
            'birthday'		=> 'required|date'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardian()
    {
        return $this->belongsTo('BibleBowl\User');
    }

    /**
     * @return null|int
     */
    public function age()
    {
        if (!is_null($this->birthday)) {
            return $this->birthday->age;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

}
