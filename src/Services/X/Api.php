<?php
/**
 * X (formerly Twitter) API Service
 *
 * Homepage:    https://phpfashion.com/twitter-for-php
 * Github: https://github.com/dg/twitter-php
 * X API: https://developer.x.com/en/docs/x-api
 */

namespace Toolkito\Larasap\Services\X;

use Toolkito\Larasap\Services\X\Exceptions\XApiException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

/**
 * X API.
 */
class Api
{
    /**
     * @var string
     */
    private static $consumerKey;

    /**
     * @var string
     */
    private static $consumerSecret;

    /**
     * @var string
     */
    private static $accessToken;

    /**
     * @var string
     */
    private static $accessTokenSecret;

    /**
     * @var bool
     */
    protected static $test_mode = false;

    public function __construct()
    {
        self::$consumerKey = Config::get('larasap.x.consumer_key');
        self::$consumerSecret = Config::get('larasap.x.consumer_secret');
        self::$accessToken = Config::get('larasap.x.access_token');
        self::$accessTokenSecret = Config::get('larasap.x.access_token_secret');

        if (empty(self::$consumerKey) || empty(self::$consumerSecret) || 
            empty(self::$accessToken) || empty(self::$accessTokenSecret)) {
            throw new XApiException('X API credentials are not properly configured');
        }
    }

    /**
     * Initialize the X API with credentials
     *
     * @return self
     * @throws Exception
     */
    public static function init()
    {
        return new self();
    }

    /**
     * Enable test mode
     *
     * @return void
     */
    public static function enableTestMode()
    {
        self::$test_mode = true;
    }

    /**
     * Disable test mode
     *
     * @return void
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
     * Send a message to X
     *
     * @param string $message The message to send
     * @param array|string|null $media Path to a local media file or array of media paths
     * @param array $options Additional options for the tweet
     * @return array|bool Response data on success, false on failure
     * @throws Exception|GuzzleException
     */
    public function sendMessage($message, $media = null, $options = [])
    {
        if (self::$test_mode) {
            return [
                'data' => [
                    'id' => '1234567890',
                    'text' => $message
                ]
            ];
        }

        try {
            $params = ['text' => $message];

            // Handle media attachments
            if ($media) {
                $mediaIds = [];
                $media = is_array($media) ? $media : [$media];
                
                foreach ($media as $mediaPath) {
                    if (count($mediaIds) >= 4) {
                        throw new XApiException('Maximum of 4 media attachments allowed per tweet');
                    }
                    $mediaIds[] = $this->uploadMedia($mediaPath);
                }
                
                $params['media'] = ['media_ids' => $mediaIds];
            }

            // Handle reply to tweet
            if (!empty($options['reply_to'])) {
                $params['reply'] = ['in_reply_to_tweet_id' => $options['reply_to']];
            }

            // Handle quote tweet
            if (!empty($options['quote_tweet_id'])) {
                $params['quote_tweet_id'] = $options['quote_tweet_id'];
            }

            // Handle poll
            if (!empty($options['poll'])) {
                if (count($options['poll']['options']) < 2 || count($options['poll']['options']) > 4) {
                    throw new XApiException('Poll must have between 2 and 4 options');
                }
                $params['poll'] = [
                    'options' => $options['poll']['options'],
                    'duration_minutes' => $options['poll']['duration_minutes'] ?? 1440
                ];
            }

            // Handle location
            if (!empty($options['location'])) {
                $params['geo'] = [
                    'place_id' => $options['location']['place_id']
                ];
            }

            // Handle scheduled time
            if (!empty($options['scheduled_time'])) {
                $params['scheduled_time'] = $options['scheduled_time'];
            }

            $response = Http::withHeaders([
                'Authorization' => $this->getAuthorizationHeader('POST', 'https://api.x.com/2/tweets', $params),
                'Content-Type' => 'application/json',
            ])->post('https://api.x.com/2/tweets', $params);

            if (!$response->successful()) {
                throw new XApiException('Failed to post to X: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new XApiException('Error posting to X: ' . $e->getMessage());
        }
    }

    /**
     * Upload media to X
     *
     * @param string $media_path Path to the media file
     * @return string|null Media ID on success, null on failure
     * @throws Exception|GuzzleException
     */
    protected function uploadMedia($mediaPath)
    {
        if (self::$test_mode) {
            return '1234567890';
        }

        try {
            // Check file type and size
            $mimeType = mime_content_type($mediaPath);
            $fileSize = filesize($mediaPath);
            
            // Validate file type
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'video/mp4',
                'video/quicktime'
            ];
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new XApiException('Unsupported media type: ' . $mimeType);
            }
            
            // Validate file size (5MB for images, 512MB for videos)
            $maxSize = strpos($mimeType, 'video/') === 0 ? 512 * 1024 * 1024 : 5 * 1024 * 1024;
            if ($fileSize > $maxSize) {
                throw new XApiException('Media file exceeds maximum size limit');
            }

            $media = base64_encode(file_get_contents($mediaPath));
            $params = ['media_data' => $media];

            $response = Http::withHeaders([
                'Authorization' => $this->getAuthorizationHeader('POST', 'https://upload.x.com/1.1/media/upload.json', $params),
                'Content-Type' => 'application/json',
            ])->post('https://upload.x.com/1.1/media/upload.json', $params);

            if (!$response->successful()) {
                throw new XApiException('Failed to upload media to X: ' . $response->body());
            }

            return $response->json()['media_id_string'];
        } catch (\Exception $e) {
            throw new XApiException('Error uploading media to X: ' . $e->getMessage());
        }
    }

    private function getAuthorizationHeader($method, $url, $params = [])
    {
        $oauth = [
            'oauth_consumer_key' => self::$consumerKey,
            'oauth_nonce' => bin2hex(random_bytes(16)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => self::$accessToken,
            'oauth_version' => '1.0'
        ];

        $oauth = array_merge($oauth, $params);
        ksort($oauth);

        $baseString = $method . '&' . rawurlencode($url) . '&';
        $baseString .= rawurlencode(http_build_query($oauth));

        $key = rawurlencode(self::$consumerSecret) . '&' . rawurlencode(self::$accessTokenSecret);
        $signature = base64_encode(hash_hmac('sha1', $baseString, $key, true));

        $oauth['oauth_signature'] = $signature;

        $header = 'OAuth ';
        $values = [];
        foreach ($oauth as $key => $value) {
            if (strpos($key, 'oauth_') === 0) {
                $values[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
            }
        }
        $header .= implode(', ', $values);

        return $header;
    }
}
