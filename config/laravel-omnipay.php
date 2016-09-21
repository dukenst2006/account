<?php

return [

    // The default gateway to use
    'default' => 'stripe',

    // Add in each gateway here
    'gateways' => [
        'stripe' => [
            'driver'  => 'Stripe',
            'options' => [
                'apiKey'   => env('STRIPE_SECRET_KEY'),
            ],
        ],
    ],

];
