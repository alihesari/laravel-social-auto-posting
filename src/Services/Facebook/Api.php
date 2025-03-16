<?php

namespace Alihesari\Larasap\Services\Facebook;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\GraphNodes\GraphNode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
     * Debug mode flag
     *
     * @var bool
     */
    private static $debug_mode = false;

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
     * Enable debug mode
     */
    public static function enableDebugMode()
    {
        self::$debug_mode = true;
    }

    /**
     * Disable debug mode
     */
    public static function disableDebugMode()
    {
        self::$debug_mode = false;
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
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public static function isDebugMode()
    {
        return self::$debug_mode;
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
        self::$fb = new Facebook([
            'app_id' => self::$app_id,
            'app_secret' => self::$app_secret,
            'default_graph_version' => self::$default_graph_version,
            'default_access_token' => self::$page_access_token,
            'enable_beta_mode' => Config::get('larasap.facebook.enable_beta_mode', false),
            'http_client_handler' => Config::get('larasap.facebook.http_client_handler', null),
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
     * @param array $options Additional options for the post
     * @return string|bool Post ID on success, false on failure
     * @throws \Exception
     */
    public static function sendLink($link, $message = '', $options = [])
    {
        if (self::$test_mode) {
            return '123456789';
        }

        self::initialize();

        try {
            $data = array_merge([
                'message' => $message,
                'link' => $link,
                'published' => $options['published'] ?? true,
                'scheduled_publish_time' => $options['scheduled_publish_time'] ?? null,
                'backdated_time' => $options['backdated_time'] ?? null,
                'backdated_time_granularity' => $options['backdated_time_granularity'] ?? null,
                'child_attachments' => $options['child_attachments'] ?? null,
                'expanded_height' => $options['expanded_height'] ?? null,
                'expanded_width' => $options['expanded_width'] ?? null,
                'full_picture' => $options['full_picture'] ?? null,
                'is_hidden' => $options['is_hidden'] ?? false,
                'is_pinned' => $options['is_pinned'] ?? false,
                'is_expired' => $options['is_expired'] ?? false,
                'message_tags' => $options['message_tags'] ?? null,
                'og_action_type_id' => $options['og_action_type_id'] ?? null,
                'og_icon_id' => $options['og_icon_id'] ?? null,
                'og_object_id' => $options['og_object_id'] ?? null,
                'og_phrase' => $options['og_phrase'] ?? null,
                'og_set_profile_badge' => $options['og_set_profile_badge'] ?? null,
                'place' => $options['place'] ?? null,
                'privacy' => $options['privacy'] ?? null,
                'targeting' => $options['targeting'] ?? null,
                'user_selected_tags' => $options['user_selected_tags'] ?? null,
                'with_tags' => $options['with_tags'] ?? null,
            ], array_filter($options));

            if (self::$debug_mode) {
                Log::debug('Facebook API Request:', ['endpoint' => '/' . Config::get('larasap.facebook.page_id') . '/feed', 'data' => $data]);
            }

            $response = self::$fb->post('/' . Config::get('larasap.facebook.page_id') . '/feed', $data);
            $graphNode = $response->getGraphNode();
            return $graphNode['id'];
        } catch (FacebookResponseException $e) {
            if (self::$debug_mode) {
                Log::error('Facebook Graph API Error:', ['error' => $e->getMessage(), 'code' => $e->getCode()]);
            }
            throw new \Exception('Facebook Graph API Error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            if (self::$debug_mode) {
                Log::error('Facebook SDK Error:', ['error' => $e->getMessage()]);
            }
            throw new \Exception('Facebook SDK Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (self::$debug_mode) {
                Log::error('Facebook API Error:', ['error' => $e->getMessage()]);
            }
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }

    /**
     * Send photo and text message
     *
     * @param string $photo Path to photo file or URL
     * @param string $message
     * @param array $options Additional options for the photo
     * @return string|bool Post ID on success, false on failure
     * @throws \Exception
     */
    public static function sendPhoto($photo, $message = '', $options = [])
    {
        if (self::$test_mode) {
            return '123456789';
        }

        self::initialize();

        try {
            $data = array_merge([
                'message' => $message,
                'source' => new \CURLFile($photo),
                'published' => $options['published'] ?? true,
                'scheduled_publish_time' => $options['scheduled_publish_time'] ?? null,
                'backdated_time' => $options['backdated_time'] ?? null,
                'backdated_time_granularity' => $options['backdated_time_granularity'] ?? null,
                'is_hidden' => $options['is_hidden'] ?? false,
                'is_expired' => $options['is_expired'] ?? false,
                'message_tags' => $options['message_tags'] ?? null,
                'place' => $options['place'] ?? null,
                'privacy' => $options['privacy'] ?? null,
                'targeting' => $options['targeting'] ?? null,
                'user_selected_tags' => $options['user_selected_tags'] ?? null,
                'with_tags' => $options['with_tags'] ?? null,
            ], array_filter($options));

            if (self::$debug_mode) {
                Log::debug('Facebook API Request:', ['endpoint' => '/' . Config::get('larasap.facebook.page_id') . '/photos', 'data' => $data]);
            }

            $response = self::$fb->post('/' . Config::get('larasap.facebook.page_id') . '/photos', $data);
            $graphNode = $response->getGraphNode();
            return $graphNode['id'];
        } catch (FacebookResponseException $e) {
            if (self::$debug_mode) {
                Log::error('Facebook Graph API Error:', ['error' => $e->getMessage(), 'code' => $e->getCode()]);
            }
            throw new \Exception('Facebook Graph API Error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            if (self::$debug_mode) {
                Log::error('Facebook SDK Error:', ['error' => $e->getMessage()]);
            }
            throw new \Exception('Facebook SDK Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (self::$debug_mode) {
                Log::error('Facebook API Error:', ['error' => $e->getMessage()]);
            }
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }

    /**
     * Send video
     *
     * @param string $video Path to video file or URL
     * @param string $title
     * @param string $description
     * @param array $options Additional options for the video
     * @return string|bool Post ID on success, false on failure
     * @throws \Exception
     */
    public static function sendVideo($video, $title = '', $description = '', $options = [])
    {
        if (self::$test_mode) {
            return '123456789';
        }

        self::initialize();

        try {
            $data = array_merge([
                'title' => $title,
                'description' => $description,
                'source' => new \CURLFile($video),
                'published' => $options['published'] ?? true,
                'scheduled_publish_time' => $options['scheduled_publish_time'] ?? null,
                'backdated_time' => $options['backdated_time'] ?? null,
                'backdated_time_granularity' => $options['backdated_time_granularity'] ?? null,
                'is_hidden' => $options['is_hidden'] ?? false,
                'is_expired' => $options['is_expired'] ?? false,
                'message_tags' => $options['message_tags'] ?? null,
                'place' => $options['place'] ?? null,
                'privacy' => $options['privacy'] ?? null,
                'targeting' => $options['targeting'] ?? null,
                'user_selected_tags' => $options['user_selected_tags'] ?? null,
                'with_tags' => $options['with_tags'] ?? null,
                'file_url' => $options['file_url'] ?? null,
                'content_category' => $options['content_category'] ?? null,
                'embeddable' => $options['embeddable'] ?? null,
                'end_offset' => $options['end_offset'] ?? null,
                'file_size' => $options['file_size'] ?? null,
                'formatting' => $options['formatting'] ?? null,
                'length' => $options['length'] ?? null,
                'original_fov' => $options['original_fov'] ?? null,
                'original_projection_type' => $options['original_projection_type'] ?? null,
                'start_offset' => $options['start_offset'] ?? null,
                'swap_mode' => $options['swap_mode'] ?? null,
                'thumb_offset' => $options['thumb_offset'] ?? null,
                'unpublished_content_type' => $options['unpublished_content_type'] ?? null,
            ], array_filter($options));

            if (self::$debug_mode) {
                Log::debug('Facebook API Request:', ['endpoint' => '/' . Config::get('larasap.facebook.page_id') . '/videos', 'data' => $data]);
            }

            $response = self::$fb->post('/' . Config::get('larasap.facebook.page_id') . '/videos', $data);
            $graphNode = $response->getGraphNode();
            return $graphNode['id'];
        } catch (FacebookResponseException $e) {
            if (self::$debug_mode) {
                Log::error('Facebook Graph API Error:', ['error' => $e->getMessage(), 'code' => $e->getCode()]);
            }
            throw new \Exception('Facebook Graph API Error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            if (self::$debug_mode) {
                Log::error('Facebook SDK Error:', ['error' => $e->getMessage()]);
            }
            throw new \Exception('Facebook SDK Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (self::$debug_mode) {
                Log::error('Facebook API Error:', ['error' => $e->getMessage()]);
            }
            throw new \Exception('Facebook API Error: ' . $e->getMessage());
        }
    }
}