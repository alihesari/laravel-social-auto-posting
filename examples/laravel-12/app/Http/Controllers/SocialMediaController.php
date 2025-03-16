<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alihesari\Larasap\SendTo;

class SocialMediaController extends Controller
{
    /**
     * Test Telegram posting
     */
    public function testTelegram()
    {
        try {
            $result = SendTo::telegram('Test message from Laravel 12');
            return response()->json(['telegram' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Test X (Twitter) posting
     */
    public function testX()
    {
        try {
            $result = SendTo::x('Test tweet from Laravel 12');
            return response()->json(['x' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Test Facebook posting
     */
    public function testFacebook()
    {
        try {
            // Enable debug mode for detailed logging
            \Alihesari\Larasap\Services\Facebook\Api::enableDebugMode();

            // Log the configuration (without sensitive data)
            \Illuminate\Support\Facades\Log::debug('Facebook API Configuration:', [
                'app_id' => config('larasap.facebook.app_id'),
                'page_id' => config('larasap.facebook.page_id'),
                'debug_mode' => config('larasap.facebook.debug_mode'),
                'beta_mode' => config('larasap.facebook.enable_beta_mode')
            ]);

            // Format the request data properly
            $data = [
                'link' => 'https://example.com',
                'message' => 'Test post from Laravel 12'
            ];

            // Add privacy settings if needed
            if (config('larasap.facebook.default_privacy.value')) {
                $data['privacy'] = [
                    'value' => config('larasap.facebook.default_privacy.value', 'EVERYONE')
                ];
            }

            $result = SendTo::facebook('link', $data);

            if (!$result || !isset($result['id'])) {
                throw new \Exception('Invalid response from Facebook API');
            }

            return response()->json(['facebook' => $result]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Facebook API Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Test all social media platforms
     */
    public function testAll()
    {
        try {
            // Test Telegram posting
            $telegramResult = SendTo::telegram('Test message from Laravel 12');
            
            // Test X (Twitter) posting
            $xResult = SendTo::x('Test tweet from Laravel 12');
            
            // Test Facebook posting
            $facebookResult = SendTo::facebook('link', [
                'link' => 'https://example.com',
                'message' => 'Test post from Laravel 12',
                'privacy' => [
                    'value' => 'EVERYONE'
                ]
            ]);
            
            return response()->json([
                'telegram' => $telegramResult,
                'x' => $xResult,
                'facebook' => $facebookResult
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 