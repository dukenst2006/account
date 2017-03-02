<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Receipt.
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
 * @property-read \App\Address $address
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReceiptItem[] $items
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt wherePaymentReferenceNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereCreatedAt($value)
 * @mixin \Eloquent
 *
 * @property int $tournament_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spectator[] $spectators
 * @property-read \App\Tournament $tournament
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TournamentQuizmaster[] $tournamentQuizmasters
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Receipt whereTournamentId($value)
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
