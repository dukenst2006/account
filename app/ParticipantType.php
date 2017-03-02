<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimException;

/**
 * BibleBowl\ParticipantType.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Event[] $events
 * @property-read mixed $summary
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\ParticipantFee[] $participantFee
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantType whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ParticipantType extends Model
{
    const TEAM = 1;
    const PLAYER = 2;
    const QUIZMASTER = 3;
    const ADULT = 4;
    const FAMILY = 5;

    protected $guarded = ['id'];

    public function getSummaryAttribute() : string
    {
        return str_replace('Spectator - ', '', $this->name);
    }

    public function participantFee() : HasMany
    {
        return $this->hasMany(ParticipantFee::class);
    }

    public function events() : HasMany
    {
        return $this->hasMany(Event::class);
    }

    public static function sku(Tournament $tournament, int $participantTypeId)
    {
        $sku = null;
        if (self::TEAM == $participantTypeId) {
            $sku = Team::REGISTRATION_SKU;
        } elseif (self::PLAYER == $participantTypeId) {
            $sku = Player::REGISTRATION_SKU;
        } elseif (self::QUIZMASTER == $participantTypeId) {
            $sku = TournamentQuizmaster::REGISTRATION_SKU;
        } elseif (self::ADULT == $participantTypeId) {
            $sku = Spectator::REGISTRATION_ADULT_SKU;
        } elseif (self::FAMILY == $participantTypeId) {
            $sku = Spectator::REGISTRATION_FAMILY_SKU;
        }

        if ($sku == null) {
            throw RuntimException('Trying to generate a sku for a ParticipationType that does not exist');
        }

        if ($tournament->hasEarlyBirdRegistrationFee($participantTypeId)) {
            $sku .= '_EARLY_BIRD';
        }

        return $sku;
    }
}
