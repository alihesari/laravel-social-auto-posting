<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Toolkito\Larasap\SendTo;

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
            $result = SendTo::facebook([
                'message' => 'Test post from Laravel 12'
            ]);
            return response()->json(['facebook' => $result]);
        } catch (\Exception $e) {
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
            $facebookResult = SendTo::facebook([
                'message' => 'Test post from Laravel 12'
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