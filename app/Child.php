<?php namespace BibleBowl;

use Config;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;

class Child extends Model {

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
        static::creating(function ($user) {
            $user->guid = Uuid::uuid5(Uuid::NAMESPACE_DNS, Config::get('biblebowl.uuid.name'));
            return true;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardian()
    {
        return $this->belongsTo('BibleBowl\User');
    }

}
