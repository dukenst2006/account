<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\UserProvider
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $provider_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \BibleBowl\User $user
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\UserProvider whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\UserProvider whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\UserProvider whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\UserProvider whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\UserProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\UserProvider whereUpdatedAt($value)
 */
class UserProvider extends Model {

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
        return $this->belongsTo('BibleBowl\User');
    }

}
