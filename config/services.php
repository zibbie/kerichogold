<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    
    'tpay' => [
        'merchant_id' => env('TPAY_MERCHANT_ID'),
        'security_code' => env('TPAY_SECURITY_CODE'),
        'crc' => env('TPAY_CRC'),
        'api_url' => env('TPAY_API_URL', 'https://secure.tpay.com/api/gw/'),
        'return_url' => env('APP_URL') . '/payment/status',
        'result_url' => env('APP_URL') . '/api/payment/webhook',
    ],

    'apaczka' => [
        'app_id' => env('APACZKA_APP_ID'),
        'app_secret' => env('APACZKA_APP_SECRET'),
        'api_url' => env('APACZKA_API_URL', 'https://www.apaczka.pl/api/v2/'),
    ],

    'przelewy24' => [
        'merchant_id' => env('P24_MERCHANT_ID'),
        'pos_id' => env('P24_POS_ID'),
        'api_key' => env('P24_API_KEY'),
        'crc' => env('P24_CRC'),
        'env' => env('P24_ENV', 'sandbox'),
        'test_url' => env('P24_TEST_URL'),
        'return_url' => env('APP_URL') . '/payment/status',
        'status_url' => env('APP_URL') . '/api/payment/p24/webhook',
    ],

    'inpost' => [
        'geowidget_token' => env('INPOST_GEOWIDGET_TOKEN'),
        'geowidget_env' => env('INPOST_GEOWIDGET_ENV', 'production'),
    ],

    'baselinker' => [
        'token' => env('BASELINKER_API_TOKEN'),
        'api_url' => 'https://api.baselinker.com/connector.php',
        'inventory_id' => env('BASELINKER_INVENTORY_ID'),
        'order_source_id' => env('BASELINKER_ORDER_SOURCE_ID', 0),
        'invoice_series_id' => env('BASELINKER_INVOICE_SERIES_ID'),
        'status_map' => [
            // Nevro status => BaseLinker status_id (klient konfiguruje po założeniu konta)
            'pending'    => env('BL_STATUS_PENDING', 0),
            'paid'       => env('BL_STATUS_PAID', 0),
            'processing' => env('BL_STATUS_PROCESSING', 0),
            'shipped'    => env('BL_STATUS_SHIPPED', 0),
            'completed'  => env('BL_STATUS_COMPLETED', 0),
            'cancelled'  => env('BL_STATUS_CANCELLED', 0),
            'refunded'   => env('BL_STATUS_REFUNDED', 0),
        ],
    ],

    'gtm' => [
        'server_url' => env('ANALYTICS_SERVER_URL'),
        'measurement_id' => env('GOOGLE_ANALYTICS_ID'),
        'api_secret' => env('GA_API_SECRET'),
    ],

];
