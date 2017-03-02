<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 *
 * @property int $tournament_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Spectator[] $spectators
 * @property-read \BibleBowl\Tournament $tournament
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\TournamentQuizmaster[] $tournamentQuizmasters
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Receipt whereTournamentId($value)
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

    public function tournamentQuizmasters() : HasMany
    {
        return $this->hasMany(TournamentQuizmaster::class);
    }

    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function items() : HasMany
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function spectators() : HasMany
    {
        return $this->hasMany(Spectator::class);
    }
}
