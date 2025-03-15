<?php

namespace Toolkito\Larasap\Tests\Unit;

use Toolkito\Larasap\Tests\TestCase;
use Toolkito\Larasap\Services\Twitter\Api;

class TwitterApiTest extends TestCase
{
    protected $api;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        config([
            'larasap.twitter.consumer_key' => 'test_consumer_key',
            'larasap.twitter.consumer_secret' => 'test_consumer_secret',
            'larasap.twitter.access_token' => 'test_access_token',
            'larasap.twitter.access_token_secret' => 'test_access_token_secret'
        ]);
        
        $this->api = new Api();
    }

    /**
     * Test that the API class can be instantiated
     */
    public function testApiClassExists()
    {
        $this->assertInstanceOf(Api::class, $this->api);
    }

    /**
     * Test that the API has required methods
     */
    public function testApiMethods()
    {
        $this->assertTrue(method_exists($this->api, 'initialize'));
        $this->assertTrue(method_exists($this->api, 'sendMessage'));
        $this->assertTrue(method_exists($this->api, 'request'));
    }

    /**
     * Test API initialization
     */
    public function testApiInitialization()
    {
        $this->assertInstanceOf(Api::class, $this->api);
        $this->api->initialize();
        $this->assertTrue(true); // If we get here without exceptions, initialization was successful
    }
} 