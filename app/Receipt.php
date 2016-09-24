<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Receipt.
 *
 * @property int $id
 * @property string $total
 * @property string $payment_reference_number
 * @property string $first_name
 * @property string $last_name
 * @property int $user_id
 * @property int $address_id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \BibleBowl\Address $address
 * @property-read \BibleBowl\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\ReceiptItem[] $items
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt wherePaymentReferenceNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereCreatedAt($value)
 * @mixin \Eloquent
 */
class Receipt extends Model
{
    protected $guarded = ['id'];

    public function setFirstNameAttribute($attribute)
    {
        $this->attributes['first_name'] = ucwords(strtolower(trim($attribute)));
    }

    public function setLastNameAttribute($attribute)
    {
        $this->attributes['last_name'] = ucwords(strtolower(trim($attribute)));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentQuizmasters()
    {
        return $this->hasMany(TournamentQuizmaster::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spectators()
    {
        return $this->hasMany(Spectator::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventPlayers()
    {
        return $this->hasMany(EventPlayer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function players()
    {
        return $this->hasManyThrough(Player::class, EventPlayer::class);
    }
}
