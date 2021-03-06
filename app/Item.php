<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Item.
 *
 * @property int $id
 * @property int $user_id
 * @property int $cart_id
 * @property int $order_id
 * @property string $sku
 * @property float $price
 * @property float $tax
 * @property float $shipping
 * @property string $currency
 * @property int $quantity
 * @property string $class
 * @property string $reference_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \App\Cart $cart
 * @property-read \App\Receipt $order
 * @property-read mixed $has_object
 * @property-read mixed $object
 * @property-read mixed $display_name
 * @property-read mixed $shop_id
 * @property-read mixed $display_price
 * @property-read mixed $display_tax
 * @property-read mixed $display_shipping
 * @property-read mixed $was_purchased
 * @property-read mixed $is_shoppable
 * @property-read mixed $shop_url
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereCartId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereSku($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Item extends Model
{
    protected $fillable = [
        'cart_id',
        'sku',
        'price',
        'quantity',
    ];

    /**
     * Build a user friendly display name based
     * on the SKU.
     *
     * @return string
     */
    public function name() : string
    {
        // seasonal registrations
        $seasonalGroupRegistrationPrefix = 'SEASON_REG_';
        $firstYearSuffix = '_FIRST_YEAR';
        if (starts_with($this->sku, $seasonalGroupRegistrationPrefix)) {
            $suffix = '';
            if (ends_with($this->sku, $firstYearSuffix)) {
                $suffix .= ' (First Year Discount)';
            }

            $program = Program::where('slug', str_replace([
                $seasonalGroupRegistrationPrefix,
                $firstYearSuffix,
            ], '', $this->sku))->firstOrFail();

            return $program->name.' Seasonal Registration'.$suffix;
        }

        // tournament registrations
        $tournamentRegistrationPrefix = 'TOURNAMENT_REG_';
        if (starts_with($this->sku, $tournamentRegistrationPrefix)) {
            $pieces = explode('_', $this->sku);
            unset($pieces[0]);
            unset($pieces[1]);

            // remove early bird from description so we can append it after
            if ($isEarlyBird = ends_with($this->sku, 'EARLY_BIRD')) {
                array_pop($pieces);
                array_pop($pieces);
            }

            if (starts_with($this->sku, $tournamentRegistrationPrefix.'EVENT')) {
                $eventType = EventType::find($pieces[3]);
                $description = $eventType->participantType->name.' '.$eventType->name;
            } else {
                $description = ucwords(strtolower(implode(' ', $pieces))).' Tournament';
            }

            $description .= ' Registration';

            if ($isEarlyBird) {
                $description .= ' (Early Bird)';
            }

            return $description;
        }

        return $this->sku;
    }

    public function cart() : BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
