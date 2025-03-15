<?php

return [

    'telegram' => [
        'api_token' => '',
        'bot_username' => '',
        'channel_username' => '', // Channel username to send message
        'channel_signature' => '', // This will be assigned in the footer of message
        'proxy' => false,   // True => Proxy is On | False => Proxy Off
    ],

    'x' => [
        'consumer_key' => '',
        'consumer_secret' => '',
        'access_token' => '',
        'access_token_secret' => ''
    ],

    'facebook' => [
        'app_id' => '', // Your Meta App ID
        'app_secret' => '', // Your Meta App Secret
        'default_graph_version' => 'v19.0', // Meta Graph API version
        'page_access_token' => '', // Your Facebook Page Access Token
        'page_id' => '', // Your Facebook Page ID
        'enable_beta_mode' => false, // Enable beta mode for testing new features
        'http_client_handler' => null, // Custom HTTP client handler
        'debug_mode' => false, // Enable debug mode for detailed logging
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
