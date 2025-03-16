<?php

namespace Alihesari\Larasap\Tests\Unit;

use Alihesari\Larasap\Tests\TestCase;
use Alihesari\Larasap\Services\Facebook\Api;
use Illuminate\Support\Facades\Http;

class FacebookApiTest extends TestCase
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
        $this->assertTrue(method_exists($this->api, 'sendLink'));
        $this->assertTrue(method_exists($this->api, 'sendPhoto'));
        $this->assertTrue(method_exists($this->api, 'sendVideo'));
    }

    public function testSendLink()
    {
        $result = $this->api->sendLink('https://example.com', 'Test message');
        $this->assertEquals('123456789', $result);
    }

    public function testSendPhoto()
    {
        $result = $this->api->sendPhoto(__DIR__ . '/../fixtures/test.jpg', 'Test message');
        $this->assertEquals('123456789', $result);
    }

    public function testSendVideo()
    {
        $result = $this->api->sendVideo(__DIR__ . '/../fixtures/test.mp4', 'Test Video', 'Test Description');
        $this->assertEquals('123456789', $result);
    }

    public function testConfigValidation()
    {
        Api::disableTestMode();
        $this->app['config']->set('larasap.facebook.app_id', '');
        $this->app['config']->set('larasap.facebook.app_secret', '');
        $this->app['config']->set('larasap.facebook.access_token', '');
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Facebook API credentials are not properly configured');
        
        new Api();
    }

    public function testTestMode()
    {
        Api::enableTestMode();
        $this->assertTrue(Api::isTestMode());
        
        Api::disableTestMode();
        $this->assertFalse(Api::isTestMode());
    }
} 