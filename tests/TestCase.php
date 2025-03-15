<?php

namespace Toolkito\Larasap\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Toolkito\Larasap\LarasapServiceProvider;
use Toolkito\Larasap\Facades\X;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up config values
        config([
            'larasap.telegram.channel_signature' => '',
            'larasap.x.consumer_key' => 'test_key',
            'larasap.x.consumer_secret' => 'test_secret',
            'larasap.x.access_token' => 'test_token',
            'larasap.x.access_token_secret' => 'test_token_secret',
            'larasap.facebook.app_id' => 'test_app_id',
            'larasap.facebook.app_secret' => 'test_app_secret',
            'larasap.facebook.access_token' => 'test_access_token',
        ]);

        // Mock HTTP requests
        Http::fake([
            // Mock Telegram API requests
            'https://api.telegram.org/bot*' => Http::response(['ok' => true], 200),
            
            // Mock X API requests
            'https://api.x.com/1.1/*' => Http::response(['id' => '123456789'], 200),
            'https://upload.x.com/1.1/*' => Http::response(['media_id_string' => '123456789'], 200),
            
            // Mock Facebook API requests
            'https://graph.facebook.com/*' => Http::response(['id' => '123456789'], 200),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            LarasapServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('larasap.x.consumer_key', 'test_key');
        $app['config']->set('larasap.x.consumer_secret', 'test_secret');
        $app['config']->set('larasap.x.access_token', 'test_token');
        $app['config']->set('larasap.x.access_token_secret', 'test_token_secret');
    }

    protected function getPackageAliases($app)
    {
        return [
            'X' => X::class,
        ];
    }

    protected function mockXApi()
    {
        Http::fake([
            'https://api.x.com/1.1/*' => Http::response(['id' => '123456789'], 200),
            'https://upload.x.com/1.1/*' => Http::response(['media_id_string' => '123456789'], 200),
        ]);
    }
} 