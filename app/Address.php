<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jackpopp\GeoDistance\GeoDistanceTrait;

/**
 * App\Address.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $address_one
 * @property string $address_two
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property float $latitude
 * @property float $longitude
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $group
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereAddressOne($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereAddressTwo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereDeletedAt($value)
 * @method static \App\Address within($distance, $measurement = null, $lat = null, $lng = null)
 * @method static \App\Address outside($distance, $measurement = null, $lat = null, $lng = null)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receipt[] $invoices
 * @mixin \Eloquent
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receipt[] $receipts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spectator[] $spectators
 */
class Address extends Model
{
    use GeoDistanceTrait;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getLatColumn()
    {
        return $this->getTable().'.latitude';
    }

    public function getLngColumn()
    {
        return $this->getTable().'.longitude';
    }

    public function lat($latitude = null)
    {
        if ($latitude) {
            $this->latitude = $latitude;

            return $this;
        }

        return $this->latitude;
    }

    public function lng($longitude = null)
    {
        if ($longitude) {
            $this->longitude = $longitude;

            return $this;
        }

        return $this->longitude;
    }

    /**
     * @return bool
     */
    public function isOwnedByUser()
    {
        return !is_null($this->user_id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function group()
    {
        return $this->hasMany(Group::class);
    }

    public static function validationRules()
    {
        return [
            'name'              => 'required|max:32',
            'address_one'       => 'required|max:255',
            'address_two'       => 'max:255',
            'zip_code'          => 'required|regex:/\b\d{5}\b/',
        ];
    }

    public static function validationMessages()
    {
        return [
            'address_one.required'  => 'The street address field is required',
            'zip_code.regex'        => 'The zip code field is invalid',
        ];
    }

    public function setNameAttribute($attribute)
    {
        $this->attributes['name'] = ucwords(strtolower(trim($attribute)));
    }

    public function setAddressOneAttribute($attribute)
    {
        $this->attributes['address_one'] = ucwords(strtolower(trim($attribute)));
    }

    public function setAddressTwoAttribute($attribute)
    {
        if (!empty($attribute)) {
            $this->attributes['address_two'] = ucwords(strtolower(trim($attribute)));
        } else {
            $this->attributes['address_two'] = null;
        }
    }

    public function setCityAttribute($attribute)
    {
        $this->attributes['city'] = ucwords(strtolower(trim($attribute)));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class)->orderBy('created_at', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spectators()
    {
        return $this->hasMany(Spectator::class);
    }

    public function __toString()
    {
        $address = $this->address_one;

        if (!empty($this->address_two)) {
            $address .= ' '.$this->address_two;
        }

        return $address.' '.$this->city.', '.$this->state.' '.$this->zip_code;
    }
}
