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

    /**urce' => self::$fb->fileToUpload($photo)
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
    public static function sendVideo($video , $title = '', $description = '')
    {
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