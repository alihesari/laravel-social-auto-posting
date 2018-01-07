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

namespace Toolkito\Larasap;

use Illuminate\Support\Facades\Config;
use Toolkito\Larasap\Telegram\Api AS TelegramApi;
use Toolkito\Larasap\Twitter\Api AS TwitterApi;
use Toolkito\Larasap\Facebook\Api AS FacebookApi;

class SendTo
{
    /**
     * Sent to Telegram
     *
     * @param $text
     * @param string $attachment
     * @param string $inline_keyboard
     * @return bool|mixed
     */
    public static function Telegram($text, $attachment = '', $inline_keyboard = '')
    {
        if (Config::get('larasap.telegram.channel_signature')) {
            $type = isset($attachment['type']) ? 'caption' : 'text';
            $text = self::assignSignature($text, $type);
        }

        if ($attachment) {
            switch ($attachment['type']) {
                case 'photo':
                    $result = TelegramApi::sendPhoto(null, $attachment['file'], $text, $inline_keyboard);
                    break;
                case 'audio':
                    $duration = isset($attachment['duration']) ? $attachment['duration'] : '';
                    $performer = isset($attachment['performer']) ? $attachment['performer'] : '';
                    $title = isset($attachment['title']) ? $attachment['title'] : '';
                    $result = TelegramApi::sendAudio(null, $attachment['file'], $text, $duration, $performer, $title, $inline_keyboard);
                    break;
                case 'document':
                    $result = TelegramApi::sendDocument(null, $attachment['file'], $text, $inline_keyboard);
                    break;
                case 'video':
                    $duration = isset($attachment['duration']) ? $attachment['duration'] : '';
                    $width = isset($attachment['width']) ? $attachment['width'] : '';
                    $height = isset($attachment['height']) ? $attachment['height'] : '';
                    $result = TelegramApi::sendVideo(null, $attachment['file'], $duration, $width, $height, $text, $inline_keyboard);
                    break;
                case 'voice':
                    $duration = isset($attachment['duration']) ? $attachment['duration'] : '';
                    $result = TelegramApi::sendVoice(null, $attachment['file'], $text, $duration, $inline_keyboard);
                    break;
                case 'media_group':
                    $result = TelegramApi::sendMediaGroup(null, json_encode($attachment['files']));
                    break;
                case 'location':
                    $live_period = isset($attachment['live_period']) ? $attachment['live_period'] : '';
                    $result = TelegramApi::sendLocation(null, $attachment['latitude'], $attachment['longitude'], $live_period, $inline_keyboard);
                    break;
                case 'venue':
                    $foursquare_id = isset($attachment['foursquare_id']) ? $attachment['foursquare_id'] : '';
                    $result = TelegramApi::sendVenue(null, $attachment['latitude'], $attachment['longitude'], $attachment['title'], $attachment['address'], $foursquare_id, $inline_keyboard);
                    break;
                case 'contact':
                    $last_name = isset($attachment['last_name']) ? $attachment['last_name'] : '';
                    $result = TelegramApi::sendContact(null, $attachment['phone_number'], $attachment['first_name'], $last_name, $inline_keyboard);
                    break;
            }
        } else {
            $result = TelegramApi::sendMessage(null, $text, $inline_keyboard);
        }

        return $result;
    }

    /**
     * Send message to Twitter
     *
     * @param $message
     * @param null $media
     * @param array $options
     * @return Twitter\stdClass
     */
    public static function Twitter($message, $media = [], $options = [])
    {
        return TwitterApi::sendMessage($message, $media, $options);
    }

    /**
     * Send message to Facebook page
     *
     * @param $type
     * @param $data
     * @return bool
     */
    public static function Facebook($type, $data)
    {
        switch ($type) {
            case 'link':
                $message = isset($data['message']) ? $data['message'] : '';
                $result = FacebookApi::sendLink($data['link'], $data['message']);
                break;
            case 'photo':
                $message = isset($data['message']) ? $data['message'] : '';
                $result = FacebookApi::sendPhoto($data['photo'], $message);
                break;
            case 'video':
                $description = isset($data['description']) ? $data['description'] : '';
                $result = FacebookApi::sendVideo($data['video'], $data['title'], $description);
                break;
        }

        return ($result > 0) ? true : false;
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
}