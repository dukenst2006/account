<?php

namespace BibleBowl;

use Amsgames\LaravelShop\Models\ShopCartModel;
use BibleBowl\Seasons\SeasonalRegistrationPaymentReceived;
use BibleBowl\Shop\PostPurchaseEvent;
use BibleBowl\Shop\UnrecognizedPurchaseEventException;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Cart
 *
 * @property integer $id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \BibleBowl\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Item[] $items
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
 * @property-read mixed $calculations_cache_key
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopCartModel current()
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopCartModel whereUser($userId)
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopCartModel whereCurrent()
 * @method static \Illuminate\Database\Query\Builder|\Amsgames\LaravelShop\Models\ShopCartModel findByUser($userId)
 * @property string $post_purchase_event
 */
class Cart extends Model
{
    protected $fillable = [
        'user_id'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Listing these events here allow us to grab
     * the object associated with these events
     * after the checkout process has completed
     *
     * @var array
     */
    protected $postPurchaseEvents = [
        SeasonalRegistrationPaymentReceived::EVENT => SeasonalRegistrationPaymentReceived::class
    ];

    /**
     * Once the checkout process is complete, an event will be
     * fired with it's own set of data.
     *
     * @param PostPurchaseEvent $event
     */
    public function setPostPurchaseEvent(PostPurchaseEvent $event)
    {
        // fail quick if we can't handle this event
        if (!array_key_exists($event->event(), $this->postPurchaseEvents)) {
            throw new UnrecognizedPurchaseEventException(get_class($event).': '.$event->event());
        }

        $this->metadata = $event->toArray();

        return $this;
    }

    /**
     * @return PostPurchaseEvent
     */
    public function postPurchaseEvent()
    {
        // the "metadata" array keys come from PostPurchaseEvent
        return app(
            $this->postPurchaseEvents[$this->metadata['event']],
            [$this->metadata]
        );
    }

    /**
     * @return []
     */
    public function triggerPostPurchaseEvent()
    {
        DB::beginTransaction();
        $this->postPurchaseEvent()->fire();
        DB::commit();
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
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * @param string    $sku
     * @param float     $price
     * @param int       $quantity
     * @return Item
     */
    public function add($sku, $price, $quantity = 1)
    {
        return $this->items()->create([
            'sku'       => $sku,
            'quantity'  => $quantity,
            'price'     => $price
        ]);
    }

    /**
     * @return string
     */
    public function total()
    {
        return number_format($this->items()->sum(DB::raw('quantity * price')), 2);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->items()->delete();

        return $this;
    }
}
