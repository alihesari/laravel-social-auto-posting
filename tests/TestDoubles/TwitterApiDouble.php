<?php

namespace Toolkito\Larasap\Tests\TestDoubles;

use Toolkito\Larasap\Services\Twitter\Api;

class TwitterApiDouble extends Api
{
    public static function request($resource, $method, array $data = null, array $files = null)
    {
        return (object)['id' => '123456789'];
    }
} 