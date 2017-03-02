<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReceiptItem.
 *
 * @property int $id
 * @property int $receipt_id
 * @property string $sku
 * @property string $description
 * @property int $quantity
 * @property float $price
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Receipt $receipt
 *
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem whereReceiptId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem whereSku($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReceiptItem whereCreatedAt($value)
 * @mixin \Eloquent
 */
class ReceiptItem extends Model
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
