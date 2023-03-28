<?php

namespace App\Http\Middleware;

use App\Services\ForSendingMessages\SendingToTelegramService;
use Closure;
use RushApp\Core\Services\LoggingService;

class ServerErrorsHandle
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $regexError500 = preg_match('/5[0-9][0-9]/', $response->getStatusCode());
        if ($regexError500 !== 0) {
            LoggingService::critical('ServerErrorsHandle - '.$response->getContent());
            SendingToTelegramService::sendMessage([
                'ERROR' => ' 500 in ServerErrorsHandle',
                'MESSAGE' => $response->getContent()
            ]);

            return response()->json(['error' => __('response_messages.error_500')], 500);
        } else {
            return $response;
        }
    }
}
