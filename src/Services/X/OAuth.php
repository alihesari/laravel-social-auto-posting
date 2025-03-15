<?php

namespace Toolkito\Larasap\Services\X;

class X_OAuthConsumer
{
    public $key;
    public $secret;

    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }
}

class X_OAuthSignatureMethod_HMAC_SHA1
{
    public function get_name()
    {
        return "HMAC-SHA1";
    }

    public function build_signature($request, $consumer, $token)
    {
        $base_string = $request->get_signature_base_string();
        $key_parts = [
            $consumer->secret,
            ($token) ? $token->secret : ""
        ];

        $key_parts = array_map('rawurlencode', $key_parts);
        $key = implode('&', $key_parts);

        return base64_encode(hash_hmac('sha1', $base_string, $key, true));
    }
}

class X_OAuthRequest
{
    private $parameters;
    private $http_method;
    private $http_url;

    public function __construct($http_method, $http_url, $parameters = [])
    {
        $this->parameters = $parameters;
        $this->http_method = $http_method;
        $this->http_url = $http_url;
    }

    public static function from_consumer_and_token($consumer, $token, $http_method, $http_url, $parameters = [])
    {
        $defaults = [
            "oauth_version" => "1.0",
            "oauth_nonce" => self::generate_nonce(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $consumer->key
        ];

        if ($token) {
            $defaults['oauth_token'] = $token->key;
        }

        $parameters = array_merge($defaults, $parameters);
        return new X_OAuthRequest($http_method, $http_url, $parameters);
    }

    public function set_parameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function get_parameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    public function get_parameters()
    {
        return $this->parameters;
    }

    public function get_signable_parameters()
    {
        $params = $this->parameters;
        if (isset($params['oauth_signature'])) {
            unset($params['oauth_signature']);
        }
        return self::build_http_query($params);
    }

    public function get_signature_base_string()
    {
        $parts = [
            $this->get_normalized_http_method(),
            $this->get_normalized_http_url(),
            $this->get_signable_parameters()
        ];

        $parts = array_map('rawurlencode', $parts);
        return implode('&', $parts);
    }

    public function sign_request($signature_method, $consumer, $token)
    {
        $this->set_parameter(
            "oauth_signature_method",
            $signature_method->get_name()
        );
        $signature = $this->build_signature($signature_method, $consumer, $token);
        $this->set_parameter("oauth_signature", $signature);
        return $signature;
    }

    public function build_signature($signature_method, $consumer, $token)
    {
        return $signature_method->build_signature($this, $consumer, $token);
    }

    public function to_url()
    {
        $post_data = $this->to_postdata();
        $out = $this->get_normalized_http_url();
        if ($post_data) {
            $out .= '?' . $post_data;
        }
        return $out;
    }

    public function to_postdata()
    {
        return self::build_http_query($this->parameters);
    }

    public function get_normalized_http_method()
    {
        return strtoupper($this->http_method);
    }

    public function get_normalized_http_url()
    {
        $parts = parse_url($this->http_url);
        $port = isset($parts['port']) ? $parts['port'] : null;
        $scheme = $parts['scheme'];
        $host = $parts['host'];
        $path = isset($parts['path']) ? $parts['path'] : '';

        $port or $port = ($scheme == 'https') ? '443' : '80';

        if (($scheme == 'https' && $port != '443')
            || ($scheme == 'http' && $port != '80')) {
            $host = "$host:$port";
        }
        return "$scheme://$host$path";
    }

    private static function generate_nonce()
    {
        return md5(microtime() . mt_rand());
    }

    private static function build_http_query($params)
    {
        if (!$params) return '';

        // Urlencode both keys and values
        $keys = array_map('rawurlencode', array_keys($params));
        $values = array_map('rawurlencode', array_values($params));
        $params = array_combine($keys, $values);

        // Parameters are sorted by name, using lexicographical byte value ordering.
        // Ref: Spec: 9.1.1 (1)
        uksort($params, 'strcmp');

        $pairs = [];
        foreach ($params as $parameter => $value) {
            if (is_array($value)) {
                // If two or more parameters share the same name, they are sorted by their value
                // Ref: Spec: 9.1.1 (1)
                natsort($value);
                foreach ($value as $duplicate_value) {
                    $pairs[] = $parameter . '=' . $duplicate_value;
                }
            } else {
                $pairs[] = $parameter . '=' . $value;
            }
        }
        return implode('&', $pairs);
    }
} 