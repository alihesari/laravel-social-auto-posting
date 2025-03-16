<?php

namespace Alihesari\Larasap\Tests\Integration;

use Alihesari\Larasap\Tests\TestCase;
use Alihesari\Larasap\SendTo;
use Alihesari\Larasap\Services\Telegram\Api as TelegramApi;
use Alihesari\Larasap\Services\X\Api as XApi;
use Alihesari\Larasap\Services\Facebook\Api as FacebookApi;
use Illuminate\Support\Facades\Http;

class SocialMediaIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set default test credentials
        $this->app['config']->set([
            'larasap.telegram.api_token' => 'test_token',
            'larasap.telegram.bot_username' => 'test_bot',
            'larasap.telegram.channel_username' => 'test_channel',
            'larasap.telegram.chat_id' => 'test_chat_id',
            
            'larasap.x.consumer_key' => 'test_consumer_key',
            'larasap.x.consumer_secret' => 'test_consumer_secret',
            'larasap.x.access_token' => 'test_access_token',
            'larasap.x.access_token_secret' => 'test_access_token_secret',
            
            'larasap.facebook.app_id' => 'test_app_id',
            'larasap.facebook.app_secret' => 'test_app_secret',
            'larasap.facebook.access_token' => 'test_access_token',
            'larasap.facebook.page_id' => 'test_page_id',
        ]);

        // Enable test mode for all APIs
        TelegramApi::enableTestMode();
        XApi::enableTestMode();
        FacebookApi::enableTestMode();

        // Initialize API instances
        TelegramApi::initialize();
        $this->telegramApi = new TelegramApi();
        $this->xApi = new XApi();
        $this->facebookApi = new FacebookApi();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Disable test mode for all APIs
        TelegramApi::disableTestMode();
        XApi::disableTestMode();
        FacebookApi::disableTestMode();
    }

    /** @test */
    public function it_can_post_to_telegram_channel()
    {
        $message = "Test message from integration test " . time();
        $response = TelegramApi::sendMessage(null, $message);
        
        $this->assertTrue($response['ok']);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('message_id', $response['result']);
    }

    /** @test */
    public function it_can_post_to_x()
    {
        $tweet = "Test tweet from integration test " . time();
        $response = $this->xApi->sendMessage($tweet);
        
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('id', $response['data']);
        $this->assertNotEmpty($response['data']['id']);
    }

    /** @test */
    public function it_can_post_to_facebook_page()
    {
        $message = "Test post from integration test " . time();
        $response = $this->facebookApi::sendLink('https://example.com', $message);
        
        $this->assertNotEmpty($response);
    }

    /** @test */
    public function it_can_handle_media_uploads()
    {
        // Test image upload to X
        $imagePath = __DIR__ . '/../fixtures/test-image.jpg';
        $response = $this->xApi->sendMessage("Test tweet with media", $imagePath);
        
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('id', $response['data']);
        $this->assertNotEmpty($response['data']['id']);
    }

    /** @test */
    public function it_can_handle_error_responses()
    {
        // Disable test mode to allow real credential validation
        XApi::disableTestMode();

        // Clear all X API credentials
        $this->app['config']->set([
            'larasap.x.consumer_key' => null,
            'larasap.x.consumer_secret' => null,
            'larasap.x.access_token' => null,
            'larasap.x.access_token_secret' => null,
        ]);
        
        $this->expectException(\Alihesari\Larasap\Services\X\Exceptions\XApiException::class);
        $this->expectExceptionMessage('X API credentials are not properly configured');
        
        // Create a new instance with invalid credentials
        $this->xApi = new XApi();
    }
} 