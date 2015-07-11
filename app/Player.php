<?php namespace BibleBowl;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Player
 *
 * @property integer $id 
 * @property string $guid 
 * @property integer $guardian_id 
 * @property string $first_name 
 * @property string $last_name 
 * @property string $shirt_size 
 * @property string $gender 
 * @property string $birthday 
 * @property string $deleted_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read User $guardian 
 * @property-read \Illuminate\Database\Eloquent\Collection|Season[] $seasons 
 * @property-read mixed $full_name 
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereGuardianId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereUpdatedAt($value)
 */
class Player extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'guid'];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each user
        static::creating(function ($player) {
            $player->guid = uniqid();
            return true;
        });
    }

    public static function validationRules()
    {
        return [
            'first_name'	=> 'required|max:32',
            'last_name'		=> 'required|max:32',
            'gender'		=> 'required',
            'birthday'		=> 'required|date'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardian()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'player_season')
            ->withPivot('grade', 'shirt_size')
            ->withTimestamps();
    }

    /**
     * @return null|int
     */
    public function age()
    {
        if (!is_null($this->birthday)) {
            return $this->birthday->age;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $birthday
     */
    public function setBirthdayAttribute($birthday)
    {
        $this->attributes['birthday'] = Carbon::createFromFormat('m/d/Y', $birthday);
    }

    /**
     * Provide birthday as a Carbon object
     *
     * @param $birthday
     *
     * @return static
     */
    public function getBirthdayAttribute($birthday)
    {
        return Carbon::createFromFormat('Y-m-d', $birthday);
    }

}
