<?php

namespace Alihesari\Larasap\Tests\TestDoubles;

use Alihesari\Larasap\Services\Facebook\Api;

class FacebookApiDouble extends Api
{
    public static function initialize()
    {
        // Do nothing in test
    }

    public static function sendLink($link, $message = '', $options = [])
    {
        return '123456789';
    }
} 