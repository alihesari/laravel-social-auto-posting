![](https://i.imgur.com/j6bzKQc.jpg)

[![Build Status](https://travis-ci.org/toolkito/laravel-social-auto-posting.svg?branch=master)](https://github.com/toolkito/laravel-social-auto-posting) [![GitHub tag](https://img.shields.io/github/tag/bevacqua/awesome-badges.svg)](https://github.com/toolkito/laravel-social-auto-posting) 

# 🌈 Laravel Social Auto Posting (Larasap)

A powerful Laravel package that enables automated posting to multiple social media platforms including Telegram, X (Twitter), and Facebook. This package provides a simple and elegant way to manage your social media presence.

## 🔐 Authentication Methods

### X (Twitter) API Authentication
This package uses OAuth 1.0a for X API authentication because:
- It's better suited for server-side automated posting
- Provides simpler implementation for Laravel applications
- No need to handle token refresh flows
- Works well with Laravel's configuration system

While X also supports OAuth 2.0, OAuth 1.0a is the recommended choice for this package's use case of automated server-side posting.

### Facebook API Authentication
The package uses Facebook Graph API with Page Access Token for authentication. This provides:
- Secure access to Facebook Pages
- Long-lived tokens
- Granular permissions control
- Easy integration with Laravel's configuration system

### Telegram Bot API Authentication
Uses Telegram Bot API token for authentication, providing:
- Simple token-based authentication
- Secure communication
- Easy setup process

## 🚀 Features

### Telegram Features
- 📝 Send text messages
- 📷 Send photos with captions
- 🎵 Send audio files with metadata
- 📖 Send documents
- 📺 Send videos with metadata
- 🔊 Send voice messages
- 🎴 Send media groups (2-10 items)
- 📍 Send locations
- 📌 Send venues
- 📞 Send contacts
- 🌐 Send messages with inline keyboards
- ✏️ Edit messages and captions
- 📌 Pin/unpin messages
- 🔄 Message retry with backoff

### X (Twitter) Features
- ✨ Send text tweets
- 🖼️ Send tweets with media (up to 4 items)
- 🗣️ Reply to tweets
- 💬 Quote tweets
- 📊 Create polls
- 📍 Add location to tweets
- ⏰ Schedule tweets
- 🔄 Rate limit handling
- 🔄 Automatic retry with backoff

### Facebook Features
- 🔗 Share links with descriptions
- 📸 Post photos with captions
- 🎥 Share videos with titles and descriptions
- ⏰ Schedule posts
- 🔒 Privacy controls
- 🎯 Post targeting
- 📊 Debug mode
- 🔄 Error handling and logging

## 🔨 Installation

1. Install the package via Composer:
```sh
composer require toolkito/larasap
```

2. Publish the configuration file:
```sh
php artisan vendor:publish --tag=larasap
```

## 🔌 Configuration

Configure your social media credentials in `config/larasap.php`:

```php
'telegram' => [
    'api_token' => 'your_telegram_bot_token',
    'bot_username' => 'your_bot_username',
    'channel_username' => 'your_channel_username',
    'channel_signature' => 'your_channel_signature',
    'proxy' => false,
],

'x' => [
    'consumer_key' => 'your_consumer_key',
    'consumer_secret' => 'your_consumer_secret',
    'access_token' => 'your_access_token',
    'access_token_secret' => 'your_access_token_secret'
],

'facebook' => [
    'app_id' => 'your_app_id',
    'app_secret' => 'your_app_secret',
    'default_graph_version' => 'v19.0',
    'page_access_token' => 'your_page_access_token',
    'page_id' => 'your_page_id',
    'enable_beta_mode' => false,
    'debug_mode' => false,
]
```

### Detailed Configuration Guide

#### Telegram Configuration
- `api_token`: Your Telegram Bot API token from [@BotFather](https://t.me/botfather)
- `bot_username`: Your bot's username (without @)
- `channel_username`: Target channel username (without @)
- `channel_signature`: Text to be added at the end of each message
- `proxy`: Enable/disable proxy support (boolean)

#### X (Twitter) Configuration
- `consumer_key`: Your X API consumer key
- `consumer_secret`: Your X API consumer secret
- `access_token`: Your X API access token
- `access_token_secret`: Your X API access token secret

#### Facebook Configuration
- `app_id`: Your Meta App ID
- `app_secret`: Your Meta App Secret
- `default_graph_version`: Facebook Graph API version (default: v19.0)
- `page_access_token`: Your Facebook Page Access Token
- `page_id`: Your Facebook Page ID
- `enable_beta_mode`: Enable beta features (default: false)
- `debug_mode`: Enable detailed logging (default: false)

### Environment Variables
You can also set these values in your `.env` file:

```env
TELEGRAM_BOT_TOKEN=your_telegram_bot_token
TELEGRAM_BOT_USERNAME=your_bot_username
TELEGRAM_CHANNEL_USERNAME=your_channel_username
TELEGRAM_CHANNEL_SIGNATURE=your_channel_signature
TELEGRAM_PROXY=false

X_CONSUMER_KEY=your_consumer_key
X_CONSUMER_SECRET=your_consumer_secret
X_ACCESS_TOKEN=your_access_token
X_ACCESS_TOKEN_SECRET=your_access_token_secret

FACEBOOK_APP_ID=your_app_id
FACEBOOK_APP_SECRET=your_app_secret
FACEBOOK_PAGE_ACCESS_TOKEN=your_page_access_token
FACEBOOK_PAGE_ID=your_page_id
FACEBOOK_ENABLE_BETA_MODE=false
FACEBOOK_DEBUG_MODE=false
```

### Configuration Validation
The package validates all configuration values on initialization. If any required values are missing or invalid, it will throw an exception with a descriptive message.

### Configuration Caching
For better performance, the package caches the configuration values. If you need to refresh the configuration, you can clear the Laravel configuration cache:

```bash
php artisan config:clear
```

## 🕹 Usage

First, add the following to your controller:
```php
use Toolkito\Larasap\SendTo;
```

### Telegram Examples

#### Basic Text Message
```php
SendTo::telegram('Hello, I\'m testing Laravel social auto posting');
```

#### Text Message with Inline Keyboard
```php
SendTo::telegram(
    'Check out our website!',
    '',
    [
        [
            [
                'text' => 'Visit Website',
                'url' => 'https://example.com'
            ],
            [
                'text' => 'Follow Us',
                'url' => 'https://t.me/yourchannel'
            ]
        ]
    ]
);
```

#### Send Photo with Caption
```php
SendTo::telegram(
    'Beautiful sunset! 🌅',
    [
        'type' => 'photo',
        'file' => 'https://example.com/sunset.jpg',
        'width' => 1920,
        'height' => 1080
    ]
);
```

#### Send Audio File
```php
SendTo::telegram(
    'Listen to our podcast! 🎧',
    [
        'type' => 'audio',
        'file' => 'https://example.com/podcast.mp3',
        'duration' => 1800,
        'performer' => 'Your Podcast Name',
        'title' => 'Episode 1'
    ]
);
```

#### Send Video with Thumbnail
```php
SendTo::telegram(
    'Watch our latest video! 🎥',
    [
        'type' => 'video',
        'file' => 'https://example.com/video.mp4',
        'thumb' => 'https://example.com/thumbnail.jpg',
        'duration' => 300,
        'width' => 1920,
        'height' => 1080
    ]
);
```

#### Send Document
```php
SendTo::telegram(
    'Download our whitepaper! 📄',
    [
        'type' => 'document',
        'file' => 'https://example.com/whitepaper.pdf',
        'thumb' => 'https://example.com/thumb.jpg'
    ]
);
```

#### Send Location
```php
SendTo::telegram(
    null,
    [
        'type' => 'location',
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'live_period' => 3600
    ]
);
```

### X (Twitter) Examples

#### Basic Tweet
```php
SendTo::x('Hello, I\'m testing Laravel social auto posting!');
```

#### Tweet with Media
```php
SendTo::x(
    'Check out these amazing photos! 📸',
    [
        'media' => [
            'path/to/photo1.jpg',
            'path/to/photo2.jpg',
            'path/to/photo3.jpg'
        ]
    ]
);
```

#### Reply to Tweet
```php
SendTo::x(
    'Thanks for your feedback!',
    [
        'reply_to' => '1234567890'
    ]
);
```

#### Quote Tweet
```php
SendTo::x(
    'This is a great point!',
    [
        'quote_tweet_id' => '1234567890'
    ]
);
```

#### Create Poll
```php
SendTo::x(
    'What\'s your favorite programming language?',
    [
        'poll' => [
            'options' => ['PHP', 'Python', 'JavaScript', 'Java'],
            'duration_minutes' => 1440
        ]
    ]
);
```

#### Tweet with Location
```php
SendTo::x(
    'Check out this amazing place!',
    [
        'location' => [
            'place_id' => 'abc123xyz'
        ]
    ]
);
```

### Facebook Examples

#### Share Link with Custom Message
```php
SendTo::facebook(
    'link',
    [
        'link' => 'https://github.com/toolkito/laravel-social-auto-posting',
        'message' => 'Check out this awesome package!',
        'privacy' => [
            'value' => 'EVERYONE'
        ]
    ]
);
```

#### Share Photo with Caption
```php
SendTo::facebook(
    'photo',
    [
        'photo' => 'path/to/photo.jpg',
        'message' => 'Beautiful sunset! 🌅',
        'targeting' => [
            'countries' => ['US', 'CA'],
            'age_min' => 18,
            'age_max' => 65
        ]
    ]
);
```

#### Share Video with Metadata
```php
SendTo::facebook(
    'video',
    [
        'video' => 'path/to/video.mp4',
        'title' => 'My Amazing Video',
        'description' => 'Check out this amazing video!',
        'privacy' => [
            'value' => 'FRIENDS'
        ],
        'scheduled_publish_time' => strtotime('+1 day')
    ]
);
```

#### Share with Custom Privacy Settings
```php
SendTo::facebook(
    'link',
    [
        'link' => 'https://example.com',
        'message' => 'Private post',
        'privacy' => [
            'value' => 'CUSTOM',
            'friends' => 'ALL_FRIENDS',
            'allow' => '123456789',
            'deny' => '987654321'
        ]
    ]
);
```

#### Share with Targeting
```php
SendTo::facebook(
    'photo',
    [
        'photo' => 'path/to/photo.jpg',
        'message' => 'Targeted post',
        'targeting' => [
            'countries' => ['US'],
            'regions' => ['CA'],
            'cities' => ['San Francisco'],
            'age_min' => 21,
            'age_max' => 35,
            'genders' => ['male', 'female'],
            'interests' => ['Technology', 'Programming']
        ]
    ]
);
```

## 🔒 Security Features

- SSL verification enabled by default
- Proxy support with authentication
- Secure API token handling
- Rate limiting protection
- Input validation and sanitization
- Error handling with custom exceptions

## ⚡ Performance Features

- Connection timeout: 10 seconds
- Request timeout: 30 seconds
- Automatic JSON encoding/decoding
- Efficient cURL usage
- Retry mechanism with exponential backoff
- Rate limit handling

## 🧪 Testing

The package includes comprehensive test coverage:
- Unit tests for all components
- Feature tests for integration
- Mock responses for API calls
- Test mode for development

## 📝 Notes

- All methods support test mode for development
- Message length limits are enforced (4096 chars for text, 1024 for captions)
- Proxy configuration is optional but validated when provided
- Debug mode available for Facebook API
- Beta mode support for testing new features

## 🚩 Roadmap

- [ ] Improve test coverage
- [ ] Add support for more social media platforms
- [ ] Implement queue system for better performance
- [ ] Add support for story posting
- [ ] Implement analytics tracking
- [ ] Add support for carousel posts
- [ ] Implement bulk posting features

## 📄 License

This package is open-sourced software licensed under the MIT license.

## 👥 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 🤝 Support

If you encounter any issues or have questions, please open an issue on GitHub.


