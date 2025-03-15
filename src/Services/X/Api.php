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
    private static $test_mode = false;

    public function __construct()
    {
        self::$consumerKey = Config::get('larasap.x.consumer_key');
        self::$consumerSecret = Config::get('larasap.x.consumer_secret');
        self::$accessToken = Config::get('larasap.x.access_token');
        self::$accessTokenSecret = Config::get('larasap.x.access_token_secret');
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
     * Send a message to X
     *
     * @param string $message The message to send
     * @param string|null $media_path Path to a local media file
     * @param array $options Additional options for the tweet
     * @return bool true on success
     * @throws Exception|GuzzleException
     */
    public static function sendMessage($message, $media_path = null, $options = [])
    {
        if (self::$test_mode) {
            return true;
        }

        try {
            $tweet_data = [
                'text' => $message,
            ];

            if ($media_path) {
                $media_id = self::uploadMedia($media_path);
                if ($media_id) {
                    $tweet_data['media'] = ['media_ids' => [$media_id]];
                }
            }

            // Merge additional options
            $tweet_data = array_merge($tweet_data, $options);

            $response = Http::withHeaders([
                'Authorization' => self::getAuthorizationHeader('POST', 'https://api.x.com/2/tweets', $tweet_data),
                'Content-Type' => 'application/json',
            ])->post('https://api.x.com/2/tweets', $tweet_data);

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
    protected static function uploadMedia($media_path)
    {
        if (!file_exists($media_path)) {
            throw new Exception('Media file not found: ' . $media_path);
        }

        try {
            $media = base64_encode(file_get_contents($media_path));
            $params = ['media_data' => $media];

            $response = Http::withHeaders([
                'Authorization' => self::getAuthorizationHeader('POST', 'https://upload.x.com/1.1/media/upload.json', $params),
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
