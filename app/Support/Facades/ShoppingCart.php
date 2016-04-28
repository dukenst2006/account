<?php

namespace BibleBowl\Support\Facades;

use BibleBowl\Cart;

class ShoppingCart extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return Cart::class;
    }
}
