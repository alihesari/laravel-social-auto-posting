<?php

namespace Toolkito\Larasap\Tests\TestDoubles;

use Toolkito\Larasap\Services\Telegram\Api;

class TelegramApiDouble extends Api
{
    protected static function sendRequest($method, $params = [])
    {
        return json_encode(['ok' => true]);
    }
} 