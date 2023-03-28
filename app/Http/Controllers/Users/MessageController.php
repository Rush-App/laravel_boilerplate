<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\MessageRequest;
use App\Services\ForSendingMessages\SendingEmailService;
use App\Services\ForSendingMessages\SendingToTelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RushApp\Core\Controllers\ResponseTrait;
use RushApp\Core\Controllers\ValidateTrait;

class MessageController extends Controller
{
    use ResponseTrait, ValidateTrait;

    public function consultationRequest(Request $request): JsonResponse
    {
        $this->validateRequest($request, MessageRequest::class);

        $sendMessage = SendingToTelegramService::sendMessage([
            'USER_NAME' => $request->name,
            'USER_EMAIL' => $request->email,
            'MESSAGE' => $request->message ?? '',
        ]);

        SendingEmailService::sendEmailForUser(
            $request->language,
            'email_messages.consultation_request',
            'email_messages.consultation_request_title',
        );

        SendingEmailService::sendEmailForAdmin(
            $request->name,
            $request->message ?? '',
            $request->email,
        );


        return $this->successResponse($sendMessage);
    }
}
