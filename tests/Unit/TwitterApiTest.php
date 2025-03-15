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
        $this->assertTrue(method_exists($this->api, 'sendMessage'));
        $this->assertTrue(method_exists($this->api, 'sendPhoto'));
        $this->assertTrue(method_exists($this->api, 'sendVideo'));
        $this->assertTrue(method_exists($this->api, 'sendGif'));
    }
} 