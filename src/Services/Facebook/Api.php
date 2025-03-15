<?php

namespace Toolkito\Larasap\Services\Facebook;

use FacebookAds\Api as FacebookApi;
use FacebookAds\Object\Page;
use FacebookAds\Object\Fields\PageFields;
use Illuminate\Support\Facades\Config;
use FacebookAds\Logger\CurlLogger;

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
    private static $default_graph_version;

    /**
     * Page access Token
     *
     * @var string
     */
    private static $page_access_token;

    /**
     * Facebook API instance
     *
     * @var \FacebookAds\Api
     */
    private static $fb;

    /**
     * Page instance
     *
     * @var \FacebookAds\Object\Page
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
     * Initialize the Facebook API
     *
     * @return void
     */
    public static function initialize()
    {
        if (self::$test_mode) {
            return;
        }

        self::$app_id = Config::get('larasap.facebook.app_id');
        self::$app_secret = Config::get('larasap.facebook.app_secret');
        self::$default_graph_version = Config::get('larasap.facebook.default_graph_version');
        self::$page_access_token = Config::get('larasap.facebook.access_token');

        if (!self::$app_id || !self::$app_secret || !self::$page_access_token) {
            throw new \Exception('Facebook API credentials are not properly configured.');
        }

        // Initialize the Facebook API
        self::$fb = FacebookApi::init(
            self::$app_id,
            self::$app_secret,
            self::$page_access_token
        );

        // Enable debug mode in non-production environments
        if (Config::get('app.debug')) {
            self::$fb->setLogger(new CurlLogger());
        }

        // Initialize the Page instance
        $pageId = Config::get('larasap.facebook.page_id');
        if (!$pageId) {
            throw new \Exception('Facebook Page ID is not configured.');
        }
        self::$page = new Page($pageId);
        self::$page->setData(['access_token' => self::$page_access_token]);
    }

    /**
     * Send link and text message
     *
     * @param string $link
     * @param string $message
     * @return bool true on success
     * @throws \Exception
     */
    public static function sendLink($link, $message = '')
    {
        if (self::$test_mode) {
            return true;
        }

        self::initialize();

        try {
            $response = self::$page->createFeed([
                'message' => $message,
                'link' => $link,
            ]);

            return $response->id > 0;
        } catch (\Exception $e) {
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }

    /**
     * Send photo and text message
     *
     * @param string $photo Path to photo file or URL
     * @param string $message
     * @return bool true on success
     * @throws \Exception
     */
    public static function sendPhoto($photo, $message = '')
    {
        if (self::$test_mode) {
            return true;
        }

        self::initialize();

        try {
            $response = self::$page->createPhoto([
                'message' => $message,
                'source' => $photo,
            ]);

            return $response->id > 0;
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
     * @return bool true on success
     * @throws \Exception
     */
    public static function sendVideo($video, $title = '', $description = '')
    {
        if (self::$test_mode) {
            return true;
        }

        self::initialize();

        try {
            $response = self::$page->createVideo([
                'title' => $title,
                'description' => $description,
                'source' => $video,
            ]);

            return $response->id > 0;
        } catch (\Exception $e) {
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }
}
