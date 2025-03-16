<?php

namespace Alihesari\Larasap\Services\Facebook;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\GraphNodes\GraphNode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
     * Verify page access and permissions
     *
     * @return bool
     * @throws \Exception
     */
    private static function verifyPageAccess()
    {
        try {
            $pageId = Config::get('larasap.facebook.page_id');
            
            // First verify the page access token
            $response = Http::get('https://graph.facebook.com/debug_token', [
                'input_token' => self::$page_access_token,
                'access_token' => self::$app_id . '|' . self::$app_secret
            ]);

            if (!$response->successful()) {
                throw new \Exception('Invalid page access token: ' . $response->body());
            }

            $tokenData = $response->json();
            
            if (self::$debug_mode) {
                Log::debug('Token debug data:', $tokenData);
            }

            // Verify page permissions
            $response = Http::withToken(self::$page_access_token)
                ->get('https://graph.facebook.com/' . self::$default_graph_version . '/' . $pageId . '/permissions');

            if (!$response->successful()) {
                throw new \Exception('Could not verify page permissions: ' . $response->body());
            }

            $permissions = $response->json();
            
            if (self::$debug_mode) {
                Log::debug('Page permissions:', $permissions);
            }

            return true;
        } catch (\Exception $e) {
            if (self::$debug_mode) {
                Log::error('Page access verification failed:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            throw new \Exception('Failed to verify page access: ' . $e->getMessage());
        }
    }

    /**
     * Initialize the Facebook API
     *
     * @return void
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

        if (!self::$app_id || !self::$app_secret) {
            throw new \Exception('Facebook API credentials are not properly configured.');
        }

        // Validate page ID
        $pageId = Config::get('larasap.facebook.page_id');
        if (!$pageId) {
            throw new \Exception('Facebook Page ID is not configured.');
        }

        // If we don't have a page access token, try to get one
        if (!self::$page_access_token) {
            try {
                if (self::$debug_mode) {
                    Log::debug('Attempting to get page access token...');
                }

                $response = Http::get('https://graph.facebook.com/' . self::$default_graph_version . '/' . $pageId, [
                    'fields' => 'access_token',
                    'access_token' => self::$app_id . '|' . self::$app_secret
                ]);

                if (!$response->successful()) {
                    throw new \Exception('Could not retrieve page access token: ' . $response->body());
                }

                $data = $response->json();
                if (isset($data['access_token'])) {
                    self::$page_access_token = $data['access_token'];
                    if (self::$debug_mode) {
                        Log::debug('Successfully retrieved page access token.');
                    }
                } else {
                    throw new \Exception('Could not retrieve page access token.');
                }
            } catch (\Exception $e) {
                if (self::$debug_mode) {
                    Log::error('Error getting page access token:', ['error' => $e->getMessage()]);
                }
                throw new \Exception('Could not retrieve page access token: ' . $e->getMessage());
            }
        }

        // Verify page access and permissions
        self::verifyPageAccess();
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
            // Verify page access before posting
            self::verifyPageAccess();
            
            $data = [
                'message' => $message,
                'link' => $link,
            ];

            // Add optional parameters if they exist
            if (isset($options['privacy'])) {
                $data['privacy'] = json_encode($options['privacy']);
            }
            if (isset($options['targeting'])) {
                $data['targeting'] = json_encode($options['targeting']);
            }

            // Add other optional parameters
            $optionalParams = [
                'published',
                'scheduled_publish_time',
                'backdated_time',
                'backdated_time_granularity',
                'child_attachments',
                'expanded_height',
                'expanded_width',
                'full_picture',
                'is_hidden',
                'is_pinned',
                'is_expired',
                'message_tags',
                'og_action_type_id',
                'og_icon_id',
                'og_object_id',
                'og_phrase',
                'og_set_profile_badge',
                'place',
                'user_selected_tags',
                'with_tags'
            ];

            foreach ($optionalParams as $param) {
                if (isset($options[$param])) {
                    $data[$param] = is_array($options[$param]) ? json_encode($options[$param]) : $options[$param];
                }
            }

            if (self::$debug_mode) {
                Log::debug('Facebook API Request:', [
                    'endpoint' => 'https://graph.facebook.com/' . self::$default_graph_version . '/' . Config::get('larasap.facebook.page_id') . '/feed',
                    'data' => $data
                ]);
            }

            // Use Laravel's HTTP client instead of Guzzle
            $response = Http::withToken(self::$page_access_token)
                ->post('https://graph.facebook.com/' . self::$default_graph_version . '/' . Config::get('larasap.facebook.page_id') . '/feed', $data);

            if (self::$debug_mode) {
                Log::debug('Facebook API Response:', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
            }

            if (!$response->successful()) {
                throw new \Exception('Facebook API Error: ' . $response->body());
            }

            $result = $response->json();
            if (!isset($result['id'])) {
                throw new \Exception('Facebook API Error: Invalid response format - missing post ID');
            }

            return $result['id'];
        } catch (\Exception $e) {
            if (self::$debug_mode) {
                Log::error('Facebook API Error:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            throw $e;
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
            $graphNode = $response->getGraphNode()->asArray();
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
            $graphNode = $response->getGraphNode()->asArray();
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