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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players() {
        return $this->hasMany('BibleBowl\Player')->orderBy('birthday', 'DESC');
    }

}
