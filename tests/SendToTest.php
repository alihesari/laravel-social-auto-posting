<?php

namespace Toolkito\Larasap\Tests;

use PHPUnit\Framework\TestCase;
use Toolkito\Larasap\SendTo;

class SendToTest extends TestCase
{
    /**
     * Test that the SendTo class can be instantiated
     *
     * @return void
     */
    public function testSendToClassExists()
    {
        $this->assertTrue(class_exists(SendTo::class));
    }
}
