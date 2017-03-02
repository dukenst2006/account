<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * App\Invitation.
 *
 * @property int $id
 * @property string $guid
 * @property string $status
 * @property string $type
 * @property string $email
 * @property int $group_id
 * @property int $inviter_id
 * @property int $user_id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Group $group
 * @property-read \App\User $inviter
 * @property-read \App\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereInviterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereUserId($value)
 * @mixin \Eloquent
 */
class Invitation extends Model
{
    const ACCEPTED = 'accepted';
    const DECLINED = 'declined';
    const SENT = 'sent';

    const TYPE_MANAGE_GROUP = 'manage-group';

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => self::SENT,
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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inviter() : BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
