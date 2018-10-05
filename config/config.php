<?php

return [

    'telegram' => [
        'api_token' => '',
        'bot_username' => '',
        'channel_username' => '', // Channel username to send message
        'channel_signature' => '', // This will be assigned in the footer of message
        'proxy' => false,   // True => Proxy is On | False => Proxy Off
    ],

    'twitter' => [
        'consurmer_key' => '',
        'consurmer_secret' => '',
        'access_token' => '',
        'access_token_secret' => ''
    ],

    'facebook' => [
        'app_id' => '',
        'app_secret' => '',
        'default_graph_version' => '',
        'page_access_token' => ''
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
