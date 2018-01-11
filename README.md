![](https://i.imgur.com/j6bzKQc.jpg)

[![Build Status](https://travis-ci.org/toolkito/laravel-social-auto-posting.svg?branch=master)](https://github.com/toolkito/laravel-social-auto-posting) [![GitHub tag](https://img.shields.io/github/tag/bevacqua/awesome-badges.svg)](https://github.com/toolkito/laravel-social-auto-posting) 

# 🌈 Introduction
This is a Laravel package to post your content to social networks such:
 - Telegram Channel (‌Based on [Telegram Bot API](https://core.telegram.org/bots/api))
 - Twitter
 - Facebook
 
 ## 🚀 Features:
 - 🍒 Simple. Easy to use.
 - 📝 Send text message to Telegram channel
 - 📷 Send photo to Telegram channel
 - 🎵 Send audio to Telegram channel
 - 📖 Send document to Telegram channel
 - 📺 Send video to Telegram channel
 - 🔊 Send voice to Telegram channel
 - 🎴 Send a group of photos or videos as an album to Telegram channel
 - 📍 Send location to Telegram
 - 📌 Send venue to Telegram
 - 📞 Send contact to Telegram
 - 🌐 Send message with url inline keyboard to Telegram channel
 - ✨ Send text and media to Twitter
 - 🎉 Send text and media to Facebook
 
 ## 🔨 Installation:
 1. Download and install package via composer:
 
 ```sh
 composer require toolkito/larasap
 ```
 2. Run the command below to publish the package config file: `config\larasap.php`
 ```sh
 php artisan vendor:publish --tag=larasap
 ```
 
 ## 🔌 Configuration:
 Set the social network information in the `config\larasap.php`. 
 
 ## 🕹 Usage:
 First, add the `use Toolkito\Larasap\SendTo;` in your controller.
 
 Next, send message to your Telegram channel or Twitter account. 
 
 ## 🚩 Roadmap

* Improve tests and coverage
* Improve performance

 ## 🌱 Quick examples:
 ### ⭐ Telegram examples:
 #### 📝 Send text message to Telegram:
 ```php
 SendTo::Telegram('Hello, I\'m testing Laravel social auto posting');
 ```
 #### 📷 Send photo to Telegram:
  ```php
  SendTo::Telegram(
      'Hello, I\'m testing Laravel social auto posting', // Photo caption (Optional)
      [
          'type' => 'photo', // Message type (Required)
          'file' => 'https://i.imgur.com/j6bzKQc.jpg' // Image url (Required)
      ],
      '' // Inline keyboard (Optional)
  );
  ```
 #### 🎵 Send audio to Telegram:
  ```php
SendTo::Telegram(
   'Hello, I\'m testing Laravel social auto posting', // Audio caption (Optional)
   [
       'type' => 'audio', // Message type (Required)
       'file' => 'http://example.com/let-me-be-your-lover.mp3', // Audio url (Required) 
       'duration' => 208, // Duration of the audio in seconds (Optional)
       'performer' => 'Enrique Iglesias', // Performer (Optional)
       'title' => 'Let Me Be Your Lover' // Track name (Optional)
   ],
  '' // Inline keyboard (Optional)
);
```
#### 📖 Send document to Telegram:
 ```php
SendTo::Telegram(
    'Hello, I\'m testing Laravel social auto posting', // Document caption
    [
        'type' => 'document', // Message type (Required)
        'file' => 'http://example.com/larasap.pdf', // Document url (Required)
    ],
   '' // Inline keyboard (Optional)
);
```
#### 📺 Send video to Telegram:
 ```php
SendTo::Telegram(
   'Hello, I\'m testing Laravel social auto posting', // Video caption (Optional)
   [
       'type' => 'video', // Message type (Required)
       'file' => 'http://example.com/let-me-be-your-lover.mp4', // Audio url (Required) 
       'duration' => 273, // Duration of sent video in seconds (Optional)
       'width' => 1920, // Video width (Optional)
       'height' => 1080 // Video height (Optional)
   ],
  '' // Inline keyboard (Optional)
);
```
#### 🔊 Send voice to Telegram:
 ```php
SendTo::Telegram(
   'Hello, I\'m testing Laravel social auto posting', // Voice message caption (Optional)
   [
       'type' => 'voice', // Message type (Required)
       'file' => 'https://upload.wikimedia.org/wikipedia/en/9/9f/Sample_of_%22Another_Day_in_Paradise%22.ogg', // Audio url (Required) 
       'duration' => 28 // Duration of the voice message in seconds (Optional)
   ],
  '' // Inline keyboard (Optional)
);
```
#### 🎴 Send media group to Telegram:
 ```php
SendTo::Telegram(
    null,
    [
        'type' => 'media_group', // Message type (Required)
        'files' => // Array describing photos and videos to be sent, must include 2–10 items
        [
            [
                'type' => 'photo', // Media type (Required)
                'media' => 'https://i.imgur.com/j6bzKQc.jpg', // Media url (Required)
                'caption' => 'Laravel sccial auto posting' // Media caption (Optional)
            ],
            [
                'type' => 'video', // Media type (Required)
                'media' => 'http://example.com/let-me-be-your-lover.mp4', // Media url (Required)
                'caption' => 'Let me be your lover' // Media caption (Optional)
            ]
        ]
    ]
);
```
#### 📍 Send point on the map to Telegram:
```php
SendTo::Telegram(
    null,
    [
        'type' => 'location', // Message type (Required)
        'latitude' => 36.1664345, // Latitude of the location (Required)
        'longitude' => 58.8209904, // Longitude of the location (Required)
        'live_period' => 86400, // Period in seconds for which the location will be updated (Optional)
        '' // Inline keyboard (Optional)
);
```
#### 📌 Send information about a venue to Telegram:
```php
SendTo::Telegram(
    null,
    [
        'type' => 'venue', // Message type (Required)
        'latitude' => 36.166048, // Latitude of the location (Required)
        'longitude' => 58.822121, // Longitude of the location (Required)
        'title' => 'Khayyam', // Name of the venue (Required)
        'address' => 'Neyshabur, Razavi Khorasan Province, Iran', // Address of the venue (Required)
        'foursquare_id' => '', // Foursquare identifier of the venue (Optional)
        '' // Inline keyboard (Optional)
);
```
#### 📞 Send phone contacts to Telegram:
```php
SendTo::Telegram(
    null,
    [
        'type' => 'contact', // Message type (Required)
        'phone_number' => '+12025550149', // Contact's phone number (Required)
        'first_name' => 'John', // Contact's first name (Required)
        'last_name' => 'Doe', // Contact's last name (Optional)
        '' // Inline keyboard (Optional)
    ]
);
```
#### 🌐 Send message with inline button to Telegram:
```php
SendTo::Telegram(
    'Laravel social auto posting',
    '',
    [
        [
            [
                'text' => 'Github',
                'url' => 'https://github.com/toolkito/laravel-social-auto-posting'
            ]
        ],
        [
            [
                'text' => 'Download',
                'url' => 'https://github.com/toolkito/laravel-social-auto-posting/archive/master.zip'
            ],
        ]
    ]
);
```
Or
```php
SendTo::Telegram(
    'Laravel social auto posting',
    '',
    [
        [
            [
                'text' => 'Github',
                'url' => 'https://github.com/toolkito/laravel-social-auto-posting'
            ],
            [
                'text' => 'Download',
                'url' => 'https://github.com/toolkito/laravel-social-auto-posting/archive/master.zip'
            ],
        ]
    ]
);
```
### ⭐ Twitter examples:
#### ✨ Text tweet:
```php
SendTo::Twitter('Hello, I\'m testing Laravel social auto posting');
```
#### ✨ Tweet with media:
```php
SendTo::Twitter(
    'Hello, I\'m testing Laravel social auto posting',
    [
        public_path('photo-1.jpg'),
        public_path('photo-2.jpg')
    ]
);
```
### ⭐ Facebook examples:
#### 🎉 Send link to Facebook page:
```php
SendTo::Facebook(
    'link',
    [
        'link' => 'https://github.com/toolkito/laravel-social-auto-posting',
        'message' => 'Laravel social auto posting'
    ]
);
```
#### 🎉 Send photo to Facebook page:
```php
SendTo::Facebook(
    'photo',
    [
        'photo' => public_path('img/1.jpg'),
        'message' => 'Laravel social auto posting'
    ]
);
```
#### 🎉 Send video to Facebook page:
```php
SendTo::Facebook(
    'video',
    [
        'video' => public_path('upload/1.mp4'),
        'title' => 'Let Me Be Your Lover',
        'description' => 'Let Me Be Your Lover - Enrique Iglesias'
    ]
);
```


