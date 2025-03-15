<?php

namespace Toolkito\Larasap\Services\Twitter;

class OAuth
{
    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;

    public function __construct($consumer_key, $consumer_secret, $access_token, $access_token_secret)
    {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->access_token = $access_token;
        $this->access_token_secret = $access_token_secret;
    }

    public function getOAuthHeader($method, $url, $params = [])
    {
        $oauth = [
            'oauth_consumer_key' => $this->consumer_key,
            'oauth_nonce' => $this->generateNonce(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->access_token,
            'oauth_version' => '1.0'
        ];

        $oauth = array_merge($oauth, $params);
        $oauth['oauth_signature'] = $this->generateSignature($method, $url, $oauth);

        $header = 'OAuth ';
        $values = [];
        foreach ($oauth as $key => $value) {
            $values[] = $key . '="' . rawurlencode($value) . '"';
        }
        $header .= implode(', ', $values);

        return $header;
    }

    private function generateNonce()
    {
        return md5(uniqid(mt_rand(), true));
    }

    private function generateSignature($method, $url, $params)
    {
        ksort($params);
        $baseString = $method . '&' . rawurlencode($url) . '&';
        $values = [];
        foreach ($params as $key => $value) {
            $values[] = rawurlencode($key) . '=' . rawurlencode($value);
        }
        $baseString .= rawurlencode(implode('&', $values));

        $key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->access_token_secret);
        return base64_encode(hash_hmac('sha1', $baseString, $key, true));
    }
} 