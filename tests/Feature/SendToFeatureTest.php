<?php

namespace Toolkito\Larasap\Tests\Feature;

use Toolkito\Larasap\Tests\TestCase;
use Toolkito\Larasap\SendTo;
use Toolkito\Larasap\Services\Telegram\Api as TelegramApi;
use Toolkito\Larasap\Services\X\Api as XApi;
use Toolkito\Larasap\Services\Facebook\Api as FacebookApi;

class SendToFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        config([
            'larasap.telegram.api_token' => 'test_token',
            'larasap.telegram.chat_id' => '123456789',
            'larasap.x.consumer_key' => 'test_consumer_key',
            'larasap.x.consumer_secret' => 'test_consumer_secret',
            'larasap.x.access_token' => 'test_access_token',
            'larasap.x.access_token_secret' => 'test_access_token_secret',
            'larasap.facebook.app_id' => 'test_app_id',
            'larasap.facebook.app_secret' => 'test_app_secret',
            'larasap.facebook.access_token' => 'test_access_token',
            'larasap.facebook.page_id' => '123456789'
        ]);

        // Enable test mode for all APIs
        TelegramApi::enableTestMode();
        XApi::enableTestMode();
        FacebookApi::enableTestMode();
    }

    /**
     * Test sending a message to Telegram
     */
    public function testTelegramMessage()
    {
        $result = SendTo::telegram('Test message');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    /**
     * Test sending a message to X
     */
    public function testXMessage()
    {
        $result = SendTo::x('Test message');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertEquals('1234567890', $result['data']['id']);
    }

    /**
     * Test sending a link to Facebook
     */
    public function testFacebookLink()
    {
        $result = SendTo::facebook('Test message', 'https://example.com');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Disable test mode for all APIs
        TelegramApi::disableTestMode();
        XApi::disableTestMode();
        FacebookApi::disableTestMode();
    }
} 