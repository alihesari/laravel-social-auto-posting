<?php

namespace Toolkito\Larasap\Services\Facebook;

use FacebookAds\Api as FacebookApi;
use FacebookAds\Object\Page;
use FacebookAds\Object\Fields\PageFields;
use Illuminate\Support\Facades\Config;
use FacebookAds\Logger\CurlLogger;
use Facebook\Facebook;

class Api
{
    /**
     * App ID
     *
     * @var string
     */
    private static $app_id;

    /**
     * App Secret
     *
     * @var string
     */
    private static $app_secret;

    /**
     * API Version
     *
     * @var string
     */
    private static $default_graph_version = 'v19.0';

    /**
     * Page access Token
     *
     * @var string
     */
    private static $page_access_token;

    /**
     * Facebook API instance
     *
     * @var \Facebook\Facebook
     */
    private static $fb;

    /**
     * Page instance
     *
     * @var \Facebook\GraphNodes\GraphNode
     */
    private static $page;

    /**
     * Test mode flag
     *
     * @var bool
     */
    private static $test_mode = false;

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
     * Initialize the Facebook API
     *
     * @return void
     * @throws \Exception
     */
    public function __construct()
    {
        if (self::$test_mode) {
            return;
        }

        self::$app_id = Config::get('larasap.facebook.app_id');
        self::$app_secret = Config::get('larasap.facebook.app_secret');
        self::$default_graph_version = Config::get('larasap.facebook.default_graph_version', 'v19.0');
        self::$page_access_token = Config::get('larasap.facebook.page_access_token');

        if (!self::$app_id || !self::$app_secret || !self::$page_access_token) {
            throw new \Exception('Facebook API credentials are not properly configured.');
        }

        // Initialize the Facebook API
        self::$fb = new \Facebook\Facebook([
            'app_id' => self::$app_id,
            'app_secret' => self::$app_secret,
            'default_graph_version' => self::$default_graph_version,
            'default_access_token' => self::$page_access_token,
        ]);

        // Initialize the Page instance
        $pageId = Config::get('larasap.facebook.page_id');
        if (!$pageId) {
            throw new \Exception('Facebook Page ID is not configured.');
        }
    }

    /**
     * Initialize the Facebook API
     *
     * @return void
     */
    public static function initialize()
    {
        if (self::$test_mode) {
            return;
        }

        if (!self::$fb) {
            new self();
        }
    }

    /**
     * Send link and text message
     *
     * @param string $link
     * @param string $message
     * @return string|bool Post ID on success, false on failure
     * @throws \Exception
     */
    public static function sendLink($link, $message = '')
    {
        if (self::$test_mode) {
            return '123456789';
        }

        self::initialize();

        try {
            $data = [
                'message' => $message,
                'link' => $link,
            ];

            $response = self::$fb->post('/' . Config::get('larasap.facebook.page_id') . '/feed', $data);
            $graphNode = $response->getGraphNode();
            return $graphNode['id'];
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Facebook Graph API Error: ' . $e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }

    /**
     * Send photo and text message
     *
     * @param string $photo Path to photo file or URL
     * @param string $message
     * @return string|bool Post ID on success, false on failure
     * @throws \Exception
     */
    public static function sendPhoto($photo, $message = '')
    {
        if (self::$test_mode) {
            return '123456789';
        }

        self::initialize();

        try {
            $data = [
                'message' => $message,
                'source' => new \CURLFile($photo),
            ];

            $response = self::$fb->post('/' . Config::get('larasap.facebook.page_id') . '/photos', $data);
            $graphNode = $response->getGraphNode();
            return $graphNode['id'];
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Facebook Graph API Error: ' . $e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }

    /**
     * Send video
     *
     * @param string $video Path to video file or URL
     * @param string $title
     * @param string $description
     * @return string|bool Post ID on success, false on failure
     * @throws \Exception
     */
    public static function sendVideo($video, $title = '', $description = '')
    {
        if (self::$test_mode) {
            return '123456789';
        }

        self::initialize();

        try {
            $data = [
                'title' => $title,
                'description' => $description,
                'source' => new \CURLFile($video),
            ];

            $response = self::$fb->post('/' . Config::get('larasap.facebook.page_id') . '/videos', $data);
            $graphNode = $response->getGraphNode();
            return $graphNode['id'];
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Facebook Graph API Error: ' . $e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }
}