<?php

namespace Toolkito\Larasap\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Toolkito\Larasap\Services\X\Api;

class XApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Api::enableTestMode();
    }

    protected function tearDown(): void
    {
        Api::disableTestMode();
        parent::tearDown();
    }

    public function testApiMethods()
    {
        $this->assertTrue(method_exists(Api::class, 'init'));
        $this->assertTrue(method_exists(Api::class, 'sendMessage'));
        $this->assertTrue(method_exists(Api::class, 'enableTestMode'));
        $this->assertTrue(method_exists(Api::class, 'disableTestMode'));
    }

    public function testSendMessage()
    {
        $result = Api::sendMessage('Test message');
        $this->assertTrue($result);
    }

    public function testSendMessageWithMedia()
    {
        $result = Api::sendMessage('Test message with media', __DIR__ . '/../fixtures/test.jpg');
        $this->assertTrue($result);
    }
} 