<?php

return [
    'private_paths' => [
        'temp_files' => 'app/temp_files',
        'user_avatars' => 'app/user_avatars',
    ],
    'env' => [
        'telegram_token' => env('TELEGRAM_TOKEN'),
        'telegram_chat_id' => env('TELEGRAM_CHAT_ID'),
        'db_database' => env('DB_DATABASE'),
        'db_username' => env('DB_USERNAME'),
        'db_password' => env('DB_PASSWORD'),
        'mail_from_address' => env('MAIL_FROM_ADDRESS'),
        'mail_from_name' => env('MAIL_FROM_NAME'),
        'admin_email' => env('ADMIN_EMAIL'),
        'user_front_url' => env('USER_FRONT_URL'),
    ]
];
