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

namespace Toolkito\Larasap\Telegram;

use Illuminate\Support\Facades\Config;


class Api
{
    /**
     * Text length of the message to be sent, 1-4096 characters
     */
    public const TEXT_LENGTH = 4096;

    /**
     * Caption length for the audio, document, photo, video or voice, 0-200 characters
     */
    public const CAPTION_LENGTH = 200;

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
     * Send text messages
     *
     * @param null $chat_id
     * @param $text - max length 4096 characters
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @param string $parse_mode
     * @param int $reply_to_message_id
     * @param bool $display_web_page_preview
     * @return bool|mixed
     */
    public static function sendMessage($chat_id = null, $text, $inline_keyboard = '', $reply_keyboard = '', $parse_mode = 'HTML', $disable_web_page_preview = false, $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','text', 'parse_mode', 'disable_web_page_preview', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendMessage', $params);
        return $result? $result : false;
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
     * @return bool|mixed
     */
    public static function sendPhoto($chat_id = null, $photo, $caption = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','photo', 'caption', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendPhoto', $params);
        return $result? $result : false;
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
     * @return bool|mixed
     */
    public static function sendAudio($chat_id = null, $audio, $caption = '', $duration = '', $performer = '', $title = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','audio', 'caption', 'duration', 'performer', 'title', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }

        $result = self::sendRequest('sendAudio', $params);
        return $result? $result : false;
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
     * @return bool|mixed
     */
    public static function sendDocument($chat_id = null, $document, $caption = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','document', 'caption', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendDocument', $params);
        return $result? $result : false;
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
     * @return bool|mixed
     */
    public static function sendVideo($chat_id = null, $video, $duration = '', $width = '', $height = '', $caption = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','video', 'duration','width', 'height', 'caption', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendVideo', $params);
        return $result? $result : false;
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
     * @return bool|mixed
     */
    public static function sendVoice($chat_id = null, $voice, $caption = '', $duration = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','voice', 'caption', 'duration', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendVoice', $params);
        return $result? $result : false;
    }

    /**
     * Send media group
     *
     * @param null $chat_id
     * @param $media
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @return bool|mixed
     */
    public static function sendMediaGroup($chat_id = null, $media, $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','media', 'disable_notification', 'reply_to_message_id');
        $result = self::sendRequest('sendMediaGroup', $params);
        return $result? $result : false;
    }

    /**
     * Send location
     *
     * @param null $chat_id
     * @param $latitude
     * @param $longitude
     * @param $live_period
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return bool|mixed
     */
    public static function sendLocation($chat_id = null, $latitude, $longitude, $live_period = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','latitude', 'longitude', 'live_period', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendLocation', $params);
        return $result? $result : false;
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
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return bool|mixed
     */
    public static function sendVenue($chat_id = null, $latitude, $longitude, $title, $address, $foursquare_id = '', $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','latitude', 'longitude', 'title', 'address', 'foursquare_id', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendVenue', $params);
        return $result? $result : false;
    }

    /**
     * Send contact
     *
     * @param null $chat_id
     * @param $phone_number
     * @param $first_name
     * @param $last_name
     * @param bool $disable_notification
     * @param string $reply_to_message_id
     * @param string $inline_keyboard
     * @param string $reply_keyboard
     * @return bool|mixed
     */
    public static function sendContact($chat_id = null, $phone_number, $first_name, $last_name, $inline_keyboard = '', $reply_keyboard = '', $disable_notification = false, $reply_to_message_id = '')
    {
        self::initialize();
        $chat_id = $chat_id ? $chat_id : self::$channel_username;
        $params = compact('chat_id','phone_number', 'first_name', 'last_name', 'disable_notification', 'reply_to_message_id');
        if($inline_keyboard) {
            $params['reply_markup'] = self::inlineKeyboard($inline_keyboard);
        }
        if($reply_keyboard) {
            $params['reply_markup'] = self::replyKeyboard($reply_keyboard);
        }
        $result = self::sendRequest('sendContact', $params);
        return $result? $result : false;
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
     */
    public static function setProxy()
    {
        return [
            CURLOPT_PROXY => Config::get('hostname' , '127.0.0.1'),
            CURLOPT_PROXYPORT => Config::get('larasap.proxy.port' , '9050'),
            CURLOPT_PROXYTYPE => Config::get('larasap.proxy.type' , 7),
            CURLOPT_PROXYUSERPWD => Config::get('larasap.proxy.username'.':'.'larasap.proxy.username'),
        ];
        
    }

    /**
     * Send Request to Telegram api
     *
     * @param string $method
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public static function sendRequest($method = 'sendMessage', $params)
    {
        $curl = curl_init(self::$api_url . self::$api_token . '/'. $method);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        if(self::$proxy){
            curl_setopt_array($curl, self::setProxy());
        }

        $curl_result = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code != 200) {
            if ($curl_result) {
                $curl_result = json_decode($curl_result, true);
                throw new \Exception($curl_result['description']);
            }
            throw new \Exception('an error was encountered');
        }

        return $curl_result;
    }
}
