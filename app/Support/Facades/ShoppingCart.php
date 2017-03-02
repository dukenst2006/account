<?php

namespace App\Support\Facades;

use App\Cart;

class ShoppingCart extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return Cart::class;
    }
}
