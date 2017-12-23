![](https://i.imgur.com/j6bzKQc.jpg)

# Laravel social auto posting (Larasap) 
Laravel social auto posting lets you automatically post all your content to social networks such :
 - Telegram Channel (‌Based on [Telegram Bot API](https://core.telegram.org/bots/api))
 - Twitter
 
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
 
 ## 🔨 Installation
 1. Download and install package via composer:
 
 ```
 composer require toolkito/larasap
 ```
 2. Run the command below to publish the package config file: `config\larasap.php`
 ```
 php artisan vendor:publish --tag=larasap
 ```
 ## 🔌 Configuration:
 Set the social network information in the `config\larasap.php`. 
 
 ## 🕹 Usage:
 First, add the `use Tooltiko\Larasap\SendTo;` in your controller.
 
 Next, send message to your Telegram channel or Twitter account. 
 
 ## 🌱 Quick examples:
 ### ⭐ Telegram examples:
 #### 📝 Send text message to Telegram:
 ```
 SendTo::Telegram('Hello, I\'m testing Laravel social auto posting');
 ```
 #### 📷 Send photo to Telegram:
  ```
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
  ```
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
  ```
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
  ```
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
  ```
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
  ```
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
```
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
```
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
```
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
```
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
```
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
```
SendTo::Twitter('Hello, I\'m testing Laravel social auto posting');
```
#### ✨ Tweet with media:
```
SendTo::Twitter(
    'Hello, I\'m testing Laravel social auto posting',
    [
        public_path('photo-1.jpg'),
        public_path('photo-2.jpg')
    ]
);
```

