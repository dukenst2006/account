<?php namespace BibleBowl;

use Config;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;

class Groups extends Model {

    const TYPE_BEGINNER = 0;
    const TYPE_TEEN = 1;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'guid'];

    protected $attributes = [
        'type' => self::TYPE_BEGINNER
    ];

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
    public function address() {
        return $this->belongsTo('BibleBowl\Address');
    }

}
