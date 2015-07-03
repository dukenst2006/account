<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {
    const TYPE_BEGINNER = 1;
    const TYPE_TEEN = 2;

    protected $guarded = ['id', 'guid'];

    protected $attributes = [
        'type' => self::TYPE_BEGINNER
    ];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each user
        static::creating(function ($group) {
            $group->guid = uniqid();
            return true;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address() {
        return $this->belongsTo('BibleBowl\Address');
    }

    public static function validationRules()
    {
        return [
            'name'			=> 'required|max:128',
            'type'	        => 'required',
            'owner_id'		=> 'required|exists:users,id',
            'address_id'	=> 'required|exists:addresses,id'
        ];
    }

    /**
     * Append the appropriate suffix to the
     * group name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name.' '.$this->type();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {
        return $this->belongsTo('BibleBowl\User', 'owner_id');
    }

    /**
     * @return string
     */
    public function type()
    {
        if ($this->status == self::TYPE_BEGINNER) {
            return 'Beginner Bowl';
        }

        return 'Bible Bowl';
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isOwner(User $user)
    {
        return $user->id == $this->owner_id;
    }

}
