<?php

namespace Toolkito\Larasap\Tests\Unit;

use Toolkito\Larasap\Tests\TestCase;
use Toolkito\Larasap\Services\Telegram\Api;
use Illuminate\Support\Facades\Http;

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
        $this->assertEquals(4096, Api::TEXT_LENGTH);
        $this->assertEquals(1024, Api::CAPTION_LENGTH);
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

    public function testSendMessage()
    {
        $result = $this->api->sendMessage(null, 'Test message');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendPhoto()
    {
        $result = $this->api->sendPhoto(null, __DIR__ . '/../fixtures/test.jpg', 'Test caption');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendAudio()
    {
        $result = $this->api->sendAudio(null, __DIR__ . '/../fixtures/test.mp3', 'Test caption', 180, 'Test Artist', 'Test Song');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendDocument()
    {
        $result = $this->api->sendDocument(null, __DIR__ . '/../fixtures/test.pdf', 'Test caption');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendVideo()
    {
        $result = $this->api->sendVideo(null, __DIR__ . '/../fixtures/test.mp4', 60, 1920, 1080, 'Test caption');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendVoice()
    {
        $result = $this->api->sendVoice(null, __DIR__ . '/../fixtures/test.ogg', 'Test caption', 30);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendMediaGroup()
    {
        $media = [
            ['type' => 'photo', 'media' => __DIR__ . '/../fixtures/test.jpg'],
            ['type' => 'photo', 'media' => __DIR__ . '/../fixtures/test2.jpg']
        ];
        $result = $this->api->sendMediaGroup(null, json_encode($media));
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertIsArray($result['result']);
        $this->assertCount(2, $result['result']);
    }

    public function testSendLocation()
    {
        $result = $this->api->sendLocation(null, 40.7128, -74.0060, 3600);
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendVenue()
    {
        $result = $this->api->sendVenue(null, 40.7128, -74.0060, 'Test Venue', '123 Test St', 'test123');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testSendContact()
    {
        $result = $this->api->sendContact(null, '+1234567890', 'John', 'Doe');
        $this->assertIsArray($result);
        $this->assertTrue($result['ok']);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('message_id', $result['result']);
    }

    public function testTestMode()
    {
        Api::enableTestMode();
        $this->assertTrue(Api::isTestMode());
        
        Api::disableTestMode();
        $this->assertFalse(Api::isTestMode());
    }
} 