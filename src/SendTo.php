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

namespace Alihesari\Larasap;

use Illuminate\Support\Facades\Config;
use Alihesari\Larasap\Services\Telegram\Api AS TelegramApi;
use Alihesari\Larasap\Services\X\Api AS XApi;
use Alihesari\Larasap\Services\Facebook\Api AS FacebookApi;

class SendTo
{
    /**
     * Send message to Telegram
     *
     * @param string $message
     * @param string $attachment
     * @param string $inline_keyboard
     * @return array|mixed
     */
    public static function telegram($message, $attachment = '', $inline_keyboard = '')
    {
        if (Config::get('larasap.telegram.channel_signature')) {
            $type = isset($attachment['type']) ? 'caption' : 'text';
            $message = self::assignSignature($message, $type);
        }

        $channel_username = Config::get('larasap.telegram.channel_username');
        // Ensure channel username starts with @
        if (!empty($channel_username) && strpos($channel_username, '@') !== 0) {
            $channel_username = '@' . ltrim($channel_username, '@');
        }

        if ($attachment) {
            switch ($attachment['type']) {
                case 'photo':
                    $result = TelegramApi::sendPhoto($channel_username, $attachment['file'], $message, $inline_keyboard);
                    break;
                case 'audio':
                    $duration = isset($attachment['duration']) ? $attachment['duration'] : '';
                    $performer = isset($attachment['performer']) ? $attachment['performer'] : '';
                    $title = isset($attachment['title']) ? $attachment['title'] : '';
                    $result = TelegramApi::sendAudio($channel_username, $attachment['file'], $message, $duration, $performer, $title, $inline_keyboard);
                    break;
                case 'document':
                    $result = TelegramApi::sendDocument($channel_username, $attachment['file'], $message, $inline_keyboard);
                    break;
                case 'video':
                    $duration = isset($attachment['duration']) ? $attachment['duration'] : '';
                    $width = isset($attachment['width']) ? $attachment['width'] : '';
                    $height = isset($attachment['height']) ? $attachment['height'] : '';
                    $result = TelegramApi::sendVideo($channel_username, $attachment['file'], $duration, $width, $height, $message, $inline_keyboard);
                    break;
                case 'voice':
                    $duration = isset($attachment['duration']) ? $attachment['duration'] : '';
                    $result = TelegramApi::sendVoice($channel_username, $attachment['file'], $message, $duration, $inline_keyboard);
                    break;
                case 'media_group':
                    $result = TelegramApi::sendMediaGroup($channel_username, json_encode($attachment['files']));
                    break;
                case 'location':
                    $live_period = isset($attachment['live_period']) ? $attachment['live_period'] : '';
                    $result = TelegramApi::sendLocation($channel_username, $attachment['latitude'], $attachment['longitude'], $live_period, $inline_keyboard);
                    break;
                case 'venue':
                    $foursquare_id = isset($attachment['foursquare_id']) ? $attachment['foursquare_id'] : '';
                    $result = TelegramApi::sendVenue($channel_username, $attachment['latitude'], $attachment['longitude'], $attachment['title'], $attachment['address'], $foursquare_id, $inline_keyboard);
                    break;
                case 'contact':
                    $last_name = isset($attachment['last_name']) ? $attachment['last_name'] : '';
                    $result = TelegramApi::sendContact($channel_username, $attachment['phone_number'], $attachment['first_name'], $last_name, $inline_keyboard);
                    break;
            }
        } else {
            $result = TelegramApi::sendMessage($channel_username, $message, $inline_keyboard);
        }

        return $result;
    }

    /**
     * Send message to Facebook page
     *
     * @param $type
     * @param $data
     * @return array
     */
    public static function facebook($type, $data)
    {
        switch ($type) {
            case 'link':
                $message = isset($data['message']) ? $data['message'] : '';
                $options = [];
                if (isset($data['privacy'])) {
                    $options['privacy'] = $data['privacy'];
                }
                if (isset($data['targeting'])) {
                    $options['targeting'] = $data['targeting'];
                }
                $result = FacebookApi::sendLink($data['link'], $message, $options);
                break;
            case 'photo':
                $message = isset($data['message']) ? $data['message'] : '';
                $options = [];
                if (isset($data['privacy'])) {
                    $options['privacy'] = $data['privacy'];
                }
                if (isset($data['targeting'])) {
                    $options['targeting'] = $data['targeting'];
                }
                $result = FacebookApi::sendPhoto($data['photo'], $message, $options);
                break;
            case 'video':
                $description = isset($data['description']) ? $data['description'] : '';
                $options = [];
                if (isset($data['privacy'])) {
                    $options['privacy'] = $data['privacy'];
                }
                if (isset($data['targeting'])) {
                    $options['targeting'] = $data['targeting'];
                }
                $result = FacebookApi::sendVideo($data['video'], $data['title'], $description, $options);
                break;
            default:
                $result = null;
        }

        return ['id' => $result];
    }

    /**
     * Assign channel signature in the footer of message
     *
     * @param $text
     * @param $text_type
     */
    public static function assignSignature($text, $type)
    {
        $signature = "\n" . Config::get('larasap.telegram.channel_signature');
        $signature_length = strlen($signature);
        $text_length = strlen($text);
        $max_length = ($type == 'text') ? TelegramApi::TEXT_LENGTH : TelegramApi::CAPTION_LENGTH;
        if ($signature_length + $text_length <= $max_length || $signature_length > $text_length) {
            return $text . $signature;
        }

        return substr($text, 0, $max_length - $signature_length) . $signature;
    }

    /**
     * Send message to X (formerly Twitter)
     *
     * @param string $message
     * @param array $options
     * @return array
     */
    public static function x($message, $options = [])
    {
        return XApi::init()->sendMessage($message, null, $options);
    }
}