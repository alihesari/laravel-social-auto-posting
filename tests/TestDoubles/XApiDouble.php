<?php

namespace Alihesari\Larasap\Tests\TestDoubles;

use Alihesari\Larasap\Services\X\Api;

class XApiDouble extends Api
{
    public static function init()
    {
        self::$test_mode = true;
        return new self();
    }

    public static function sendMessage($message, $media_path = null, $options = [])
    {
        return true;
    }
} 