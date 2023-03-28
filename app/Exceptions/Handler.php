<?php

namespace App\Exceptions;

use App\Services\ForSendingMessages\SendingToTelegramService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use RushApp\Core\Exceptions\CoreHttpException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception): Response
    {
        $dataForSending = [
            'handle: ' => 'App\Exceptions\Handler - render func',
            'Server name: ' => SendingToTelegramService::getServerName(),
            'Error code: ' => $exception->getCode(),
            'Error file: ' => $exception->getFile(),
            'Error line: ' => $exception->getLine(),
            'Error message: ' => $exception->getMessage(),
        ];

        SendingToTelegramService::sendMessage($dataForSending);

        return parent::render($request, $exception);
    }

    public function register()
    {
        $this->renderable(function (CustomHttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        });
        $this->renderable(function (CoreHttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        });
    }
}
