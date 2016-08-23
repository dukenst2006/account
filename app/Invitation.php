<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use BibleBowl\User;
use Rhumsaa\Uuid\Uuid;

class Invitation extends Model
{
    const ACCEPTED  = 'accepted';
    const DECLINED  = 'declined';
    const SENT      = 'sent';

    const TYPE_MANAGE_GROUP = 'manage-group';

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => Invitation::SENT
    ];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each entity
        static::creating(function ($invitation) {
            $invitation->guid = Uuid::uuid4();
            return true;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, null, 'inviter_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
