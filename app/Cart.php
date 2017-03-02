<?php

namespace App;

use App\Competition\Tournaments\Groups\RegistrationPaymentReceived as GroupRegistrationPaymentReceived;
use App\Competition\Tournaments\Quizmasters\RegistrationPaymentReceived as QuizmasterRegistrationPaymentReceived;
use App\Competition\Tournaments\Spectators\RegistrationPaymentReceived as SpectatorRegistrationPaymentReceived;
use App\Seasons\ProgramRegistrationPaymentReceived;
use App\Shop\PostPurchaseEvent;
use App\Shop\UnrecognizedPurchaseEvent;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Cart.
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Item[] $items
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
 *
 * @method static \Illuminate\Database\Query\Builder current()
 * @method static \Illuminate\Database\Query\Builder whereUser($userId)
 * @method static \Illuminate\Database\Query\Builder whereCurrent()
 * @method static \Illuminate\Database\Query\Builder findByUser($userId)
 *
 * @property string $post_purchase_event
 * @property array $metadata
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereMetadata($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    protected $attributes = [
        'user_id' => null,
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Listing these events here allow us to grab
     * the object associated with these events
     * after the checkout process has completed.
     *
     * @var array
     */
    protected $postPurchaseEvents = [
        ProgramRegistrationPaymentReceived::EVENT       => ProgramRegistrationPaymentReceived::class,

        // tournament registrations
        GroupRegistrationPaymentReceived::EVENT         => GroupRegistrationPaymentReceived::class,
        SpectatorRegistrationPaymentReceived::EVENT     => SpectatorRegistrationPaymentReceived::class,
        QuizmasterRegistrationPaymentReceived::EVENT    => QuizmasterRegistrationPaymentReceived::class,
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
            throw new UnrecognizedPurchaseEvent(get_class($event).': '.$event->event());
        }

        $this->metadata = $event->toArray();

        return $this;
    }

    /**
     * @return PostPurchaseEvent
     */
    public function postPurchaseEvent()
    {
        $eventClass = $this->postPurchaseEvents[$this->metadata['event']];
        // the "metadata" array keys come from PostPurchaseEvent
        return new $eventClass($this->metadata);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items() : HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * @param string $sku
     * @param float  $price
     * @param int    $quantity
     */
    public function add(string $sku, $price, int $quantity = 1) : Item
    {
        return $this->items()->create([
            'sku'       => $sku,
            'quantity'  => $quantity,
            'price'     => $price,
        ]);
    }

    /**
     * @return string
     */
    public function total()
    {
        return $this->items()->sum(DB::raw('quantity * price'));
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->items()->delete();

        return $this;
    }

    /**
     * @return ReceiptItem[]|\Illuminate\Support\Collection
     */
    public function receiptItems()
    {
        $receiptItems = collect();
        foreach ($this->items()->get() as $item) {
            $receiptItems[] = new ReceiptItem([
                'sku'           => $item->sku,
                'description'   => $item->name(),
                'price'         => $item->price,
                'quantity'      => $item->quantity,
            ]);
        }

        return $receiptItems;
    }
}
