<?php

namespace App\Services\ForSendingMessages;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RushApp\Core\Services\LoggingService;

class SendingToTelegramService
{
    /**
     * @param array $data
     * @return string
     */
    public static function sendMessage(array $data): string
    {
        $token = Config::get('constants.env.telegram_token');
        $chat_id = Config::get('constants.env.telegram_chat_id');

        if (!array_key_exists('Server name: ', $data)) {
            $data['Server name: '] = self::getServerName();
        }

        $txt = null;
        foreach($data as $key => $value) {
            $txt .= "<b>".$key."</b> ".$value."\n";
        }

        $response = Http::get("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chat_id,
            'parse_mode' => 'html',
            'text' => $txt,
        ]);

        if ($response->successful()) {
            return __('response_messages.message_successfully_sent');
        }

        LoggingService::critical('sendToTelegram dont send - ' . $txt);
        return __('response_messages.message_wasnt_sent');
    }

    public static function getServerName(): string
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $httpHost = $_SERVER['HTTP_HOST'] ?? '';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';

        return $protocol . "://" . $httpHost . $requestUri;
    }
}


