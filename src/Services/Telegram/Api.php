<?php
/**
 * This file is part of the Laravel social auto posting package.
 *
 * Copyright (c) 2016 Ali Hesari <alihesari.com@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Homepage:    https://github.com/alihesari/larasap
 * Version:     1.0
 */

namespace Toolkito\Larasap\Services\Telegram;

use Illuminate\Support\Facades\Config;
use Toolkito\Larasap\Services\Telegram\Exceptions\TelegramApiException;

class Api
{
    /**
     * Text length of the message to be sent, 1-4096 characters
     */
    public const TEXT_LENGTH = 4096;

    /**
     * Caption length for the audio, document, photo, video or voice, 0-1024 characters
     */
    public const CAPTION_LENGTH = 1024;

    /**
     * Telegram bot api url
     *
     * @var string
     */
    private static $api_url = 'https://api.telegram.org/bot';

    /**
     * Telegram bot api token
     *
     * @var string
     */
    private static $api_token;

    /**
     * Telegram bot username
     *
     * @var string
     */
    private static $bot_username;

    /**
     * Telegram Channel username to send messages
     *
     * @var string
     */
    private static $channel_username;

    /**
     * Proxy Status => On | Off
     *
     * @var string
     */
    private static $proxy;

    /**
     * Test mode flag
     *
     * @var bool
     */
    private static $test_mode = false;

    /**
     * Initialize
     */
    public static function initialize()
    {
        self::$api_token = Config::get('larasap.telegram.api_token');
        self::$bot_username = Config::get('larasap.telegram.bot_username');
        self::$channel_username = Config::get('larasap.telegram.channel_username');
        self::$proxy = !! Config::get('larasap.telegram.proxy');
    }

    /**
     * Enable test mode
     */
    public static function enableTestMode()
    {
        self::$test_mode = true;
    }

    /**
     * Disable test mode
     */
    public static function disableTestMode()
    {
        self::$test_mode = false;
    }

    /**
     * Check if test mode is enabled
     *
     * @return bool
     */
    public static function isTestMode()
    {
        return self::$test_mode;
    }

    /**
     * Send text messages
     *
     * @param null $chat_id
     * @param $text - max length 4096 characters
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @param string $parse_mode
     * @param int $reply_to_message_id
     * @param bool $display_web_page_preview
     * @return array|bool
     */
    public static function sendMessage($chat_id = null, $text, $inline_keyboard = '', $reply_keyboard = '', $parse_mode = 'HTML', $disable_web_page_preview = false, $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ?: Config::get('larasap.telegram.chat_id');
        $params = compact('chat_id','text', 'parse_mode', 'disable_web_page_preview', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendMessage', $params);
    }

    /**
     * Send photo
     *
     * @param null $chat_id
     * @param $photo
     * @param string $caption
     * @param bool $disable_notification
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return array|bool
     */
    public static function sendPhoto($chat_id = null, $photo, $caption = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','photo', 'caption', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendPhoto', $params);
    }

    /**
     * Send audio
     *
     * @param null $chat_id
     * @param $audio
     * @param string $caption
     * @param string $duration
     * @param string $performer
     * @param string $title
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return array|bool
     */
    public static function sendAudio($chat_id = null, $audio, $caption = '', $duration = '', $performer = '', $title = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','audio', 'caption', 'duration', 'performer', 'title', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendAudio', $params);
    }

    /**
     * Send document
     *
     * @param null $chat_id
     * @param $document
     * @param string $caption
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return array|bool
     */
    public static function sendDocument($chat_id = null, $document, $caption = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','document', 'caption', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendDocument', $params);
    }

    /**
     * Send video
     *
     * @param null $chat_id
     * @param $video
     * @param string $duration
     * @param string $width
     * @param string $height
     * @param string $caption
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return array|bool
     */
    public static function sendVideo($chat_id = null, $video, $duration = '', $width = '', $height = '', $caption = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','video', 'duration','width', 'height', 'caption', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendVideo', $params);
    }

    /**
     * Send voice
     *
     * @param null $chat_id
     * @param $voice
     * @param string $caption
     * @param string $duration
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return array|bool
     */
    public static function sendVoice($chat_id = null, $voice, $caption = '', $duration = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','voice', 'caption', 'duration', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendVoice', $params);
    }

    /**
     * Send media group
     *
     * @param null $chat_id
     * @param $media
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @return array|bool
     */
    public static function sendMediaGroup($chat_id = null, $media, $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => [
                    ['message_id' => 123],
                    ['message_id' => 124]
                ]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','media', 'disable_notification', 'reply_to_message_id');
        return self::sendRequest('sendMediaGroup', $params);
    }

    /**
     * Send location
     *
     * @param null $chat_id
     * @param $latitude
     * @param $longitude
     * @param string $live_period
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @return array|bool
     */
    public static function sendLocation($chat_id = null, $latitude, $longitude, $live_period = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','latitude', 'longitude', 'live_period', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendLocation', $params);
    }

    /**
     * Send venue
     *
     * @param null $chat_id
     * @param $latitude
     * @param $longitude
     * @param $title
     * @param $address
     * @param string $foursquare_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @return array|bool
     */
    public static function sendVenue($chat_id = null, $latitude, $longitude, $title, $address, $foursquare_id = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','latitude', 'longitude', 'title', 'address', 'foursquare_id', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendVenue', $params);
    }

    /**
     * Send contact
     *
     * @param null $chat_id
     * @param $phone_number
     * @param $first_name
     * @param $last_name
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @return array|bool
     */
    public static function sendContact($chat_id = null, $phone_number, $first_name, $last_name, $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => 123]
            ];
        }

        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','phone_number', 'first_name', 'last_name', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        return self::sendRequest('sendContact', $params);
    }

    /**
     * Create inline keyboard
     *
     * @param $buttons
     * @return string
     */
    public static function inlineKeyboard($buttons)
    {
        $inline_keyboard = ['inline_keyboard' => $buttons];
        return json_encode($inline_keyboard);
    }

    /**
     * Create reply buttons
     *
     * @param $buttons
     * @return string
     */
    public static function replyKeyboard($buttons)
    {
        $inline_keyboard = ['keyboard' => $buttons];
        return json_encode($inline_keyboard);
    }

    /**
     * Set Proxy
     *
     * @return array
     * @throws TelegramApiException
     */
    public static function setProxy()
    {
        $hostname = Config::get('larasap.proxy.hostname');
        $port = Config::get('larasap.proxy.port');
        $type = Config::get('larasap.proxy.type');
        $username = Config::get('larasap.proxy.username');
        $password = Config::get('larasap.proxy.password');

        if (!$hostname || !$port) {
            throw new TelegramApiException('Proxy hostname and port are required');
        }

        $proxyConfig = [
            CURLOPT_PROXY => $hostname,
            CURLOPT_PROXYPORT => $port,
            CURLOPT_PROXYTYPE => $type ?: CURLPROXY_SOCKS5_HOSTNAME,
        ];

        if ($username && $password) {
            $proxyConfig[CURLOPT_PROXYUSERPWD] = $username . ':' . $password;
        }

        return $proxyConfig;
    }

    /**
     * Send Request to Telegram api
     *
     * @param string $method
     * @param $params
     * @return mixed
     * @throws TelegramApiException
     */
    protected static function sendRequest($method, $params = [])
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => [
                    'message_id' => 123456789,
                    'from' => [
                        'id' => 987654321,
                        'is_bot' => true,
                        'first_name' => 'Test Bot',
                        'username' => 'test_bot'
                    ],
                    'chat' => [
                        'id' => -100123456789,
                        'title' => 'Test Channel',
                        'type' => 'channel'
                    ],
                    'date' => time(),
                    'text' => $params['text'] ?? 'Test message'
                ]
            ];
        }

        $curl = curl_init(self::$api_url . self::$api_token . '/'. $method);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

        if(self::$proxy) {
            try {
                curl_setopt_array($curl, self::setProxy());
            } catch (TelegramApiException $e) {
                curl_close($curl);
                throw $e;
            }
        }

        $curl_result = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        if ($curl_error) {
            throw new TelegramApiException(
                "cURL Error: {$curl_error}",
                $http_code
            );
        }

        if ($http_code != 200) {
            if ($curl_result) {
                $error_data = json_decode($curl_result, true);
                throw new TelegramApiException(
                    $error_data['description'] ?? 'Unknown error occurred',
                    $http_code,
                    $error_data['error_code'] ?? null,
                    $error_data['parameters'] ?? null
                );
            }
            throw new TelegramApiException(
                "HTTP Error: {$http_code}",
                $http_code
            );
        }

        return json_decode($curl_result, true);
    }

    /**
     * Edit text messages
     *
     * @param string|int $chat_id
     * @param int $message_id
     * @param string $text
     * @param string $inline_keyboard
     * @param string $parse_mode
     * @param bool $disable_web_page_preview
     * @return array|bool
     */
    public static function editMessageText($chat_id, $message_id, $text, $inline_keyboard = '', $parse_mode = 'HTML', $disable_web_page_preview = false)
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => $message_id]
            ];
        }

        self::initialize();
        $params = compact('chat_id', 'message_id', 'text', 'parse_mode', 'disable_web_page_preview');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        return self::sendRequest('editMessageText', $params);
    }

    /**
     * Edit message caption
     *
     * @param string|int $chat_id
     * @param int $message_id
     * @param string $caption
     * @param string $inline_keyboard
     * @return array|bool
     */
    public static function editMessageCaption($chat_id, $message_id, $caption, $inline_keyboard = '')
    {
        if (self::$test_mode) {
            return [
                'ok' => true,
                'result' => ['message_id' => $message_id]
            ];
        }

        self::initialize();
        $params = compact('chat_id', 'message_id', 'caption');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        return self::sendRequest('editMessageCaption', $params);
    }

    /**
     * Delete a message
     *
     * @param string|int $chat_id
     * @param int $message_id
     * @return array|bool
     */
    public static function deleteMessage($chat_id, $message_id)
    {
        if (self::$test_mode) {
            return ['ok' => true];
        }

        self::initialize();
        return self::sendRequest('deleteMessage', compact('chat_id', 'message_id'));
    }

    /**
     * Pin a message in a chat
     *
     * @param string|int $chat_id
     * @param int $message_id
     * @param bool $disable_notification
     * @return array|bool
     */
    public static function pinMessage($chat_id, $message_id, $disable_notification = false)
    {
        if (self::$test_mode) {
            return ['ok' => true];
        }

        self::initialize();
        return self::sendRequest('pinChatMessage', compact('chat_id', 'message_id', 'disable_notification'));
    }

    /**
     * Unpin a message in a chat
     *
     * @param string|int $chat_id
     * @param int $message_id
     * @return array|bool
     */
    public static function unpinMessage($chat_id, $message_id)
    {
        if (self::$test_mode) {
            return ['ok' => true];
        }

        self::initialize();
        return self::sendRequest('unpinChatMessage', compact('chat_id', 'message_id'));
    }

    /**
     * Unpin all messages in a chat
     *
     * @param string|int $chat_id
     * @return array|bool
     */
    public static function unpinAllMessages($chat_id)
    {
        if (self::$test_mode) {
            return ['ok' => true];
        }

        self::initialize();
        return self::sendRequest('unpinAllChatMessages', compact('chat_id'));
    }
}
