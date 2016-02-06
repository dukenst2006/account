<?php

namespace BibleBowl;

use Amsgames\LaravelShop\Models\ShopItemModel;
use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Item
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $cart_id
 * @property integer $order_id
 * @property string $sku
 * @property float $price
 * @property float $tax
 * @property float $shipping
 * @property string $currency
 * @property integer $quantity
 * @property string $class
 * @property string $reference_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \BibleBowl\User $user
 * @property-read \BibleBowl\Cart $cart
 * @property-read \BibleBowl\Order $order
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
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopItemModel whereSKU($sku)
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopItemModel findBySKU($sku)
 */
class Item extends Model
{
    protected $fillable = [
        'cart_id',
        'sku',
        'price',
        'quantity'
    ];

    /**
     * Build a user friendly display name based
     * on the SKU
     *
     * @return string
     */
    public function name()
    {
        // if it's a seasonal registration
        $seasonalRegistrationPrefix = 'SEASON_REG_';
        if (starts_with($this->sku, $seasonalRegistrationPrefix)) {
            $program = Program::where('slug', str_replace($seasonalRegistrationPrefix, '', $this->sku))->firstOrFail();
            return $program->name.' Seasonal Registration';
        }

        return $this->sku;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
