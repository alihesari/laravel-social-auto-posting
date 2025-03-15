<?php

require_once __DIR__.'/../vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

// Create a new container instance
$container = new Container;

// Set the container as the application instance
Facade::setFacadeApplication($container);

// Register the config service provider
$container->singleton('config', function () {
    return new \Illuminate\Config\Repository([
        'larasap' => [
            'telegram' => [
                'channel_signature' => '',
            ],
            'x' => [
                'consumer_key' => 'test_key',
                'consumer_secret' => 'test_secret',
                'access_token' => 'test_token',
                'access_token_secret' => 'test_token_secret',
            ],
            'facebook' => [
                'app_id' => 'test_app_id',
                'app_secret' => 'test_app_secret',
                'access_token' => 'test_access_token',
            ],
        ],
    ]);
}); 