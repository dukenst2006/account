<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

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
