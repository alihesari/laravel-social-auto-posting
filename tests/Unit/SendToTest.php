<?php

namespace Alihesari\Larasap\Tests\Unit;

use Alihesari\Larasap\Tests\TestCase;
use Alihesari\Larasap\SendTo;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Alihesari\Larasap\Services\Telegram\Api;
use Alihesari\Larasap\Services\X\Api as XApi;
use Alihesari\Larasap\Services\Facebook\Api as FacebookApi;

class SendToTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Enable test mode for all APIs
        Api::enableTestMode();
        XApi::enableTestMode();
        FacebookApi::enableTestMode();

        // Mock HTTP responses
        Http::fake([
            // Telegram API mocks
            'https://api.telegram.org/bot123456789:test_token/sendMessage' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendPhoto' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendAudio' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendDocument' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendVideo' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendVoice' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendMediaGroup' => Http::response([
                'ok' => true,
                'result' => [
                    ['message_id' => 123],
                    ['message_id' => 124]
                ]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendLocation' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendVenue' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),
            'https://api.telegram.org/bot123456789:test_token/sendContact' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 123]
            ], 200),

            // X API mocks
            'https://api.x.com/2/tweets' => Http::response([
                'data' => [
                    'id' => '1234567890',
                    'text' => 'Test message'
                ]
            ], 200),
            'https://upload.x.com/1.1/media/upload.json' => Http::response([
                'media_id_string' => '1234567890'
            ], 200),

            // Facebook API mocks
            'https://graph.facebook.com/*/feed' => Http::response([
                'id' => '123456789_123456789'
            ], 200),
            'https://graph.facebook.com/*/photos' => Http::response([
                'id' => '123456789_123456789'
            ], 200),
            'https://graph.facebook.com/*/videos' => Http::response([
                'id' => '123456789_123456789'
            ], 200),
        ]);
    }

    public function testTelegramMessageWithSignature()
    {
        $result = SendTo::telegram('Test message');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithAttachment()
    {
        $attachment = [
            'type' => 'photo',
            'file' => __DIR__ . '/../fixtures/test.jpg'
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithAudioAttachment()
    {
        $attachment = [
            'type' => 'audio',
            'file' => __DIR__ . '/../fixtures/test.mp3',
            'duration' => 180,
            'performer' => 'Test Artist',
            'title' => 'Test Song'
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithDocumentAttachment()
    {
        $attachment = [
            'type' => 'document',
            'file' => __DIR__ . '/../fixtures/test.pdf'
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithVideoAttachment()
    {
        $attachment = [
            'type' => 'video',
            'file' => __DIR__ . '/../fixtures/test.mp4',
            'duration' => 60,
            'width' => 1920,
            'height' => 1080
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithVoiceAttachment()
    {
        $attachment = [
            'type' => 'voice',
            'file' => __DIR__ . '/../fixtures/test.ogg',
            'duration' => 30
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithMediaGroup()
    {
        $attachment = [
            'type' => 'media_group',
            'files' => [
                ['type' => 'photo', 'media' => __DIR__ . '/../fixtures/test.jpg'],
                ['type' => 'photo', 'media' => __DIR__ . '/../fixtures/test2.jpg']
            ]
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
    }

    public function testTelegramMessageWithLocation()
    {
        $attachment = [
            'type' => 'location',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'live_period' => 3600
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithVenue()
    {
        $attachment = [
            'type' => 'venue',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'title' => 'Test Venue',
            'address' => '123 Test St',
            'foursquare_id' => 'test123'
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithContact()
    {
        $attachment = [
            'type' => 'contact',
            'phone_number' => '+1234567890',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];
        $result = SendTo::telegram('Test message', $attachment);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTelegramMessageWithInlineKeyboard()
    {
        $inline_keyboard = [
            [
                ['text' => 'Button 1', 'callback_data' => 'button1'],
                ['text' => 'Button 2', 'callback_data' => 'button2']
            ]
        ];
        $result = SendTo::telegram('Test message', '', $inline_keyboard);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testXMessageWithText()
    {
        $result = SendTo::x('Test message');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertEquals('1234567890', $result['data']['id']);
    }

    public function testXMessageWithMedia()
    {
        $attachment = [
            'type' => 'photo',
            'file' => __DIR__ . '/../fixtures/test.jpg'
        ];
        $result = SendTo::x('Test message with media', $attachment);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertEquals('1234567890', $result['data']['id']);
    }

    public function testXMessageWithOptions()
    {
        $options = [
            'reply_to' => '123456789',
            'quote_tweet_id' => '987654321'
        ];
        $result = SendTo::x('Test message', '', $options);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertEquals('1234567890', $result['data']['id']);
    }

    public function testFacebookLink()
    {
        $data = [
            'link' => 'https://example.com',
            'message' => 'Test message'
        ];
        $result = SendTo::facebook('link', $data);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('123456789', $result['id']);
    }

    public function testFacebookPhoto()
    {
        $data = [
            'photo' => __DIR__ . '/test.jpg',
            'message' => 'Test message'
        ];
        $result = SendTo::facebook('photo', $data);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('123456789', $result['id']);
    }

    public function testFacebookVideo()
    {
        $data = [
            'video' => __DIR__ . '/test.mp4',
            'title' => 'Test Video',
            'description' => 'Test Description'
        ];
        $result = SendTo::facebook('video', $data);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('123456789', $result['id']);
    }

    public function testFacebookInvalidType()
    {
        $data = [
            'message' => 'Test message'
        ];
        $result = SendTo::facebook('invalid_type', $data);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertNull($result['id']);
    }

    public function testSignatureAppending()
    {
        Config::set('larasap.telegram.channel_signature', '- Test Signature');
        $text = 'Test message';
        $result = SendTo::assignSignature($text, 'text');
        $this->assertEquals($text . "\n- Test Signature", $result);
    }

    public function testSignatureAppendingWithLongText()
    {
        Config::set('larasap.telegram.channel_signature', '- Test Signature');
        $text = str_repeat('a', 4096); // Maximum Telegram message length
        $result = SendTo::assignSignature($text, 'text');
        $this->assertLessThanOrEqual(4096, strlen($result));
        $this->assertStringEndsWith('- Test Signature', $result);
    }
} 