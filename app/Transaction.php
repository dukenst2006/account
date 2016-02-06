<?php

namespace BibleBowl;

use Amsgames\LaravelShop\Models\ShopTransactionModel;

/**
 * BibleBowl\Transaction
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $gateway
 * @property string $transaction_id
 * @property string $detail
 * @property string $token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \BibleBowl\Order $order
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopTransactionModel whereUser($userId)
 */
class Transaction extends ShopTransactionModel
{
    //
}
