<?php

namespace Toolkito\Larasap\Tests\Feature;

use Toolkito\Larasap\Tests\TestCase;
use Toolkito\Larasap\SendTo;
use Toolkito\Larasap\Services\Telegram\Api as TelegramApi;
use Toolkito\Larasap\Services\Twitter\Api as TwitterApi;
use Toolkito\Larasap\Services\Facebook\Api as FacebookApi;

class SendToFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock the API classes to avoid actual API calls during tests
        $this->telegramApi = $this->createMock(TelegramApi::class);
        $this->twitterApi = $this->createMock(TwitterApi::class);
        $this->facebookApi = $this->createMock(FacebookApi::class);
    }

    /**
     * Test sending a message to Telegram
     */
    public function testTelegramMessage()
    {
        $text = "Test message";
        $this->telegramApi->expects($this->once())
            ->method('sendMessage')
            ->with(null, $text, '')
            ->willReturn(true);

        $result = SendTo::Telegram($text);
        $this->assertTrue($result);
    }

    /**
     * Test sending a message to Twitter
     */
    public function testTwitterMessage()
    {
        $message = "Test tweet";
        $this->twitterApi->expects($this->once())
            ->method('sendMessage')
            ->with($message, [], [])
            ->willReturn(true);

        $result = SendTo::Twitter($message);
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
        $this->facebookApi->expects($this->once())
            ->method('sendLink')
            ->with($data['link'], $data['message'])
            ->willReturn(1);

        $result = SendTo::Facebook('link', $data);
        $this->assertTrue($result);
    }
} 