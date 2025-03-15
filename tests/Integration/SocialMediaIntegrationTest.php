<?php

namespace Toolkito\Larasap\Tests\Integration;

use Toolkito\Larasap\Tests\TestCase;
use Toolkito\Larasap\Facades\X;
use Toolkito\Larasap\Facades\Telegram;
use Toolkito\Larasap\Facades\Facebook;
use Illuminate\Support\Facades\Http;

class SocialMediaIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Use real API credentials for integration tests
        $this->app['config']->set([
            'larasap.telegram.api_token' => env('TELEGRAM_BOT_TOKEN'),
            'larasap.telegram.bot_username' => env('TELEGRAM_BOT_USERNAME'),
            'larasap.telegram.channel_username' => env('TELEGRAM_CHANNEL_USERNAME'),
            
            'larasap.x.consumer_key' => env('X_CONSUMER_KEY'),
            'larasap.x.consumer_secret' => env('X_CONSUMER_SECRET'),
            'larasap.x.access_token' => env('X_ACCESS_TOKEN'),
            'larasap.x.access_token_secret' => env('X_ACCESS_TOKEN_SECRET'),
            
            'larasap.facebook.app_id' => env('FACEBOOK_APP_ID'),
            'larasap.facebook.app_secret' => env('FACEBOOK_APP_SECRET'),
            'larasap.facebook.access_token' => env('FACEBOOK_ACCESS_TOKEN'),
            'larasap.facebook.page_id' => env('FACEBOOK_PAGE_ID'),
        ]);
    }

    /** @test */
    public function it_can_post_to_telegram_channel()
    {
        $message = "Test message from integration test " . time();
        $response = Telegram::sendMessage($message);
        
        $this->assertTrue($response['ok']);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('message_id', $response['result']);
    }

    /** @test */
    public function it_can_post_to_x()
    {
        $tweet = "Test tweet from integration test " . time();
        $response = X::tweet($tweet);
        
        $this->assertArrayHasKey('id', $response);
        $this->assertNotEmpty($response['id']);
    }

    /** @test */
    public function it_can_post_to_facebook_page()
    {
        $message = "Test post from integration test " . time();
        $response = Facebook::post($message);
        
        $this->assertArrayHasKey('id', $response);
        $this->assertNotEmpty($response['id']);
    }

    /** @test */
    public function it_can_handle_media_uploads()
    {
        // Test image upload to X
        $imagePath = __DIR__ . '/../fixtures/test-image.jpg';
        $response = X::uploadMedia($imagePath);
        
        $this->assertArrayHasKey('media_id_string', $response);
        $this->assertNotEmpty($response['media_id_string']);
    }

    /** @test */
    public function it_can_handle_error_responses()
    {
        // Temporarily set invalid credentials to test error handling
        $this->app['config']->set('larasap.x.access_token', 'invalid_token');
        
        $this->expectException(\Exception::class);
        X::tweet("This should fail");
    }
} 