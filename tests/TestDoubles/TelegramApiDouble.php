<?php

namespace Alihesari\Larasap\Tests\TestDoubles;

use Alihesari\Larasap\Services\Telegram\Api;

class TelegramApiDouble extends Api
{
    protected static function sendRequest($method, $params = [])
    {
        return json_encode(['ok' => true]);
    }
} 