<?php

namespace Toolkito\Larasap\Tests\TestDoubles;

use Toolkito\Larasap\Services\Facebook\Api;

class FacebookApiDouble extends Api
{
    public static function initialize()
    {
        // Do nothing in test
    }

    public static function sendLink($link, $message = '')
    {
        return '123456789';
    }
} 