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
        $text = "Test message";
        $result = SendTo::Telegram($text);
        $this->assertTrue($result);
    }

    /**
     * Test sending a message to X
     */
    public function testXMessage()
    {
        $message = "Test post";
        $result = SendTo::X($message);
        $this->assertTrue($result);
    }

    /**
     * Test sending a link to Facebook
     */
    public function testFacebookLink()
    {
        $data = [
            'link' => 'https://example.com',
            'message' => 'Test link'
        ];
        
        $result = SendTo::Facebook('link', $data);
        $this->assertTrue($result);
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