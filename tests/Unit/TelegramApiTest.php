<?php

namespace Toolkito\Larasap\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Toolkito\Larasap\Services\Telegram\Api;

class TelegramApiTest extends TestCase
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
     * Test that the API has required constants
     */
    public function testApiConstants()
    {
        $this->assertIsInt(Api::TEXT_LENGTH);
        $this->assertIsInt(Api::CAPTION_LENGTH);
    }

    /**
     * Test that the API has required methods
     */
    public function testApiMethods()
    {
        $this->assertTrue(method_exists($this->api, 'sendMessage'));
        $this->assertTrue(method_exists($this->api, 'sendPhoto'));
        $this->assertTrue(method_exists($this->api, 'sendAudio'));
        $this->assertTrue(method_exists($this->api, 'sendDocument'));
        $this->assertTrue(method_exists($this->api, 'sendVideo'));
        $this->assertTrue(method_exists($this->api, 'sendVoice'));
        $this->assertTrue(method_exists($this->api, 'sendMediaGroup'));
        $this->assertTrue(method_exists($this->api, 'sendLocation'));
        $this->assertTrue(method_exists($this->api, 'sendVenue'));
        $this->assertTrue(method_exists($this->api, 'sendContact'));
    }
} 