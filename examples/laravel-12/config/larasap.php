<?php

return [

    'telegram' => [
        'api_token' => env('TELEGRAM_BOT_TOKEN', ''),
        'bot_username' => env('TELEGRAM_BOT_USERNAME', ''),
        'channel_username' => env('TELEGRAM_CHANNEL_USERNAME', ''), // Channel username to send message
        'channel_signature' => env('TELEGRAM_CHANNEL_SIGNATURE', ''), // This will be assigned in the footer of message
        'proxy' => false,   // True => Proxy is On | False => Proxy Off
    ],

    'x' => [
        'consumer_key' => env('X_CONSUMER_KEY', ''),
        'consumer_secret' => env('X_CONSUMER_SECRET', ''),
        'access_token' => env('X_ACCESS_TOKEN', ''),
        'access_token_secret' => env('X_ACCESS_TOKEN_SECRET', '')
    ],

    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID', ''), // Your Meta App ID
        'app_secret' => env('FACEBOOK_APP_SECRET', ''), // Your Meta App Secret
        'default_graph_version' => 'v19.0', // Meta Graph API version
        'page_access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN', ''), // Your Facebook Page Access Token
        'page_id' => env('FACEBOOK_PAGE_ID', ''), // Your Facebook Page ID
        'enable_beta_mode' => env('FACEBOOK_ENABLE_BETA_MODE', false), // Enable beta mode for testing new features
        'http_client_handler' => null, // Custom HTTP client handler
        'debug_mode' => env('FACEBOOK_DEBUG_MODE', false), // Enable debug mode for detailed logging
        'default_privacy' => [ // Default privacy settings for posts
            'value' => 'EVERYONE',
            'description' => 'Public post',
            'friends' => '',
            'allow' => '',
            'deny' => '',
        ],
        'default_targeting' => [ // Default targeting settings for posts
            'countries' => [],
            'regions' => [],
            'cities' => [],
            'age_min' => null,
            'age_max' => null,
            'genders' => [],
            'college_networks' => [],
            'college_majors' => [],
            'college_years' => [],
            'interests' => [],
            'relationship_statuses' => [],
            'locales' => [],
        ],
    ],

    // Set Proxy for Servers that can not Access Social Networks due to Sanctions or ...
    'proxy' => [
        'type' => '',   // 7 for Socks5
        'hostname' => '', // localhost
        'port' => '' , // 9050
        'username' => '', // Optional
        'password' => '', // Optional
    ]
];
