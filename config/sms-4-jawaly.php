<?php

return [
    'api_key'    => env('SMS4JAWALY_API_KEY'),
    'api_secret' => env('SMS4JAWALY_API_SECRET'),
    'default_sender' => env('SMS4JAWALY_DEFAULT_SENDER'),
    'receiver_attribute' => env('SMS4JAWALY_RECEIVER_ATTRIBUTE', 'phone'),
];