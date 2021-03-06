<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserProvider.
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\App\UserProvider whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProvider whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProvider whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProvider whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProvider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserProvider extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
