<?php

namespace BibleBowl;

use Amsgames\LaravelShop\Models\ShopCouponModel;
use Amsgames\LaravelShop\Models\ShopOrderModel;

/**
 * BibleBowl\Order
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $statusCode
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \BibleBowl\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Item[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Transaction[] $transactions
 * @property-read mixed $is_locked
 * @property-read mixed $count
 * @property-read mixed $total_price
 * @property-read mixed $total_tax
 * @property-read mixed $total_shipping
 * @property-read mixed $total_discount
 * @property-read mixed $total
 * @property-read mixed $display_total_price
 * @property-read mixed $display_total_tax
 * @property-read mixed $display_total_shipping
 * @property-read mixed $display_total_discount
 * @property-read mixed $display_total
 * @property-read mixed $is_completed
 * @property-read mixed $has_failed
 * @property-read mixed $is_canceled
 * @property-read mixed $is_in_process
 * @property-read mixed $is_in_creation
 * @property-read mixed $is_pending
 * @property-read mixed $calculations_cache_key
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopOrderModel findByUser($userId, $statusCode = null)
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopOrderModel whereSKU($sku)
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopOrderModel whereStatusIn($statusCodes)
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopOrderModel whereUser($userId)
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopOrderModel whereStatus($statusCode)
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $sku
 * @property float $value
 * @property float $discount
 * @property integer $active
 * @property string $expires_at
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopCouponModel whereCode($code)
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopCouponModel findByCode($code)
 */
class Coupon extends ShopCouponModel
{
    //
}
