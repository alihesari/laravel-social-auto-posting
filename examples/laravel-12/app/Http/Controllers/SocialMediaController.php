<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Toolkito\Larasap\Facades\X;
use Toolkito\Larasap\Facades\Telegram;
use Toolkito\Larasap\Facades\Facebook;

class SocialMediaController extends Controller
{
    public function test()
    {
        try {
            // Test Telegram posting
            $telegramResult = Telegram::sendMessage('Test message from Laravel 12');
            
            // Test X (Twitter) posting
            $xResult = X::post('Test tweet from Laravel 12');
            
            // Test Facebook posting
            $facebookResult = Facebook::post([
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