<?php namespace BibleBowl;

use Carbon\Carbon;
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

    //protected $dates = ['birthday'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seasons()
    {
        return $this->belongsToMany('BibleBowl\Season', 'player_season')
            ->withPivot('grade', 'shirt_size')
            ->withTimestamps();
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

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $birthday
     */
    public function setBirthdayAttribute($birthday)
    {
        $this->attributes['birthday'] = Carbon::createFromFormat('m/d/Y', $birthday);
    }

    /**
     * Provide birthday as a Carbon object
     *
     * @param $birthday
     *
     * @return static
     */
    public function getBirthdayAttribute($birthday)
    {
        return Carbon::createFromFormat('Y-m-d', $birthday);
    }

}
