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

## 🕹 Usage

First, add the following to your controller:
```php
use Toolkito\Larasap\SendTo;
```

### Telegram Examples

#### Send Text Message
```php
SendTo::telegram('Hello, I\'m testing Laravel social auto posting');
```

#### Send Photo
```php
SendTo::telegram(
    'Photo caption', // Optional
    [
        'type' => 'photo',
        'file' => 'https://example.com/photo.jpg'
    ]
);
```

#### Send Media Group
```php
SendTo::telegram(
    null,
    [
        'type' => 'media_group',
        'files' => [
            [
                'type' => 'photo',
                'media' => 'https://example.com/photo1.jpg',
                'caption' => 'First photo'
            ],
            [
                'type' => 'video',
                'media' => 'https://example.com/video1.mp4',
                'caption' => 'First video'
            ]
        ]
    ]
);
```

### X (Twitter) Examples

#### Send Tweet
```php
SendTo::x('Hello, I\'m testing Laravel social auto posting');
```

#### Send Tweet with Media
```php
SendTo::x(
    'Check out this photo!',
    [
        'media' => ['path/to/photo.jpg']
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

### Facebook Examples

#### Share Link
```php
SendTo::facebook(
    'link',
    [
        'link' => 'https://github.com/toolkito/laravel-social-auto-posting',
        'message' => 'Check out this awesome package!'
    ]
);
```

#### Share Photo
```php
SendTo::facebook(
    'photo',
    [
        'photo' => 'path/to/photo.jpg',
        'message' => 'Beautiful sunset!'
    ]
);
```

#### Share Video
```php
SendTo::facebook(
    'video',
    [
        'video' => 'path/to/video.mp4',
        'title' => 'My Video',
        'description' => 'Check out this amazing video!'
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


