<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany('BibleBowl\Player')
            ->withPivot('grade', 'shirt_size')
            ->withTimestamps()
            ->orderBy('birthday', 'DESC');
    }

}
