<?php

namespace Alihesari\Larasap\Tests\Unit;

use Alihesari\Larasap\Tests\TestCase;
use Alihesari\Larasap\Services\X\Api;
use Alihesari\Larasap\Services\X\Exceptions\XApiException;
use Illuminate\Support\Facades\Http;

class XApiTest extends TestCase
{
    protected $api;

    protected function setUp(): void
    {
        parent::setUp();
        Api::enableTestMode();
        $this->api = new Api();
    }

    protected function tearDown(): void
    {
        Api::disableTestMode();
        parent::tearDown();
    }

    public function testApiClassExists()
    {
        $this->assertInstanceOf(Api::class, $this->api);
    }

    public function testApiMethods()
    {
        $this->assertTrue(method_exists($this->api, 'sendMessage'));
        $this->assertTrue(method_exists($this->api, 'init'));
    }

    public function testSendMessage()
    {
        $result = $this->api->sendMessage('Test message');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertEquals('1234567890', $result['data']['id']);
    }

    public function testSendMessageWithMedia()
    {
        $result = $this->api->sendMessage('Test message with media', __DIR__ . '/../fixtures/test.jpg');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertEquals('1234567890', $result['data']['id']);
    }

    public function testSendMessageWithOptions()
    {
        $options = [
            'reply_to' => '123456789',
            'quote_tweet_id' => '987654321'
        ];
        $result = $this->api->sendMessage('Test message', null, $options);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('id', $result['data']);
        $this->assertEquals('1234567890', $result['data']['id']);
    }

    public function testConfigValidation()
    {
        $this->app['config']->set('larasap.x.consumer_key', '');
        $this->app['config']->set('larasap.x.consumer_secret', '');
        $this->app['config']->set('larasap.x.access_token', '');
        $this->app['config']->set('larasap.x.access_token_secret', '');
        
        $this->expectException(XApiException::class);
        $this->expectExceptionMessage('X API credentials are not properly configured');
        
        new Api();
    }

    public function testOAuthAuthentication()
    {
        $reflectionClass = new \ReflectionClass(Api::class);
        $method = $reflectionClass->getMethod('getAuthorizationHeader');
        $method->setAccessible(true);

        $authHeader = $method->invoke($this->api, 'POST', 'https://api.x.com/2/tweets', ['text' => 'Test']);
        
        $this->assertStringStartsWith('OAuth ', $authHeader);
        $this->assertStringContainsString('oauth_consumer_key=', $authHeader);
        $this->assertStringContainsString('oauth_signature_method="HMAC-SHA1"', $authHeader);
    }

    public function testTestMode()
    {
        Api::enableTestMode();
        $this->assertTrue(Api::isTestMode());
        
        Api::disableTestMode();
        $this->assertFalse(Api::isTestMode());
    }
} 