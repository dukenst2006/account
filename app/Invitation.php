<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * BibleBowl\Invitation.
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
 * @property-read \BibleBowl\Group $group
 * @property-read \BibleBowl\User $inviter
 * @property-read \BibleBowl\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereInviterId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Invitation whereUserId($value)
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
