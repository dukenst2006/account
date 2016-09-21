<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\ReceiptItem.
 *
 * @property int $id
 * @property int $receipt_id
 * @property string $sku
 * @property string $description
 * @property int $quantity
 * @property float $price
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \BibleBowl\Receipt $receipt
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem whereReceiptId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem whereSku($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ReceiptItem whereCreatedAt($value)
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
