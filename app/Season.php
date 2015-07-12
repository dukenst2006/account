<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Season
 *
 * @property integer $id 
 * @property string $name 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Player')
 *             ->withPivot('grade[] $players 
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereUpdatedAt($value)
 */
class Season extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players() {
        // if this relation is updated, update Group too
        return $this->belongsToMany(Player::class)
            ->withPivot('group_id', 'grade', 'shirt_size')
            ->withTimestamps()
            ->orderBy('birthday', 'DESC');
    }

}
