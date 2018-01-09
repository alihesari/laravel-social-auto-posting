<?php

namespace Toolkito\Larasap\Facebook;

use Facebook;
use Illuminate\Support\Facades\Config;

class Api
{
    /**
     * App ID
     *
     * @var integer
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

    private static $fb;

    public static function initialize()
    {
        self::$app_id = Config::get('larasap.facebook.app_id');
        self::$app_secret = Config::get('larasap.facebook.app_secret');
        self::$default_graph_version = Config::get('larasap.facebook.default_graph_version');
        self::$page_access_token = Config::get('larasap.facebook.page_access_token');

        self::$fb = new \Facebook\Facebook([
            'app_id' => self::$app_id,
            'app_secret' => self::$app_secret,
            'default_graph_version' => self::$default_graph_version,
        ]);
    }

    /**
     * Send link and text message
     *
     * @param $link
     * @param $message
     * @return bool
     * @throws \Exception
     */
    public static function sendLink($link, $message = '')
    {
        self::initialize();
        $data = compact('link', 'message');
        try {
            $response = self::$fb->post('/me/feed', $data, self::$page_access_token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Graph returned an error: '.$e->getMessage());
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK returned an error: '.$e->getMessage());
        }
        $graphNode = $response->getGraphNode();

        return $graphNode['id'];
    }

    /**
     * Send photo and text message
     *
     * @param $photo
     * @param string $message
     * @return bool
     * @throws \Exception
     */
    public static function sendPhoto($photo ,$message = ''){
        self::initialize();
        $data = [
            'message' => $message,
            'source' => self::$fb->fileToUpload($photo)
        ];
        try {
            $response = self::$fb->post('/me/photos', $data, self::$page_access_token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Graph returned an error: '.$e->getMessage());
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK returned an error: '.$e->getMessage());
        }
        $graphNode = $response->getGraphNode();

        return $graphNode['id'];
    }

    /**
     * Send video
     *
     * @param $video
     * @param string $title
     * @param string $description
     * @return mixed
     * @throws \Exception
     */
    public static function sendVideo($video , $title = '', $description = ''){
        self::initialize();
        $data = compact('title','description');
        try {
            $response = self::$fb->uploadVideo('me', $video,$data, self::$page_access_token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Graph returned an error: '.$e->getMessage());
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK returned an error: '.$e->getMessage());
        }
        $graphNode = $response->getGraphNode();

        return $graphNode['id'];
    }
}
