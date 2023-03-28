<?php

namespace App\Jobs;

use App\Services\ForSendingMessages\EmailService;
use App\Services\ForSendingMessages\SendingToTelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use RushApp\Core\Services\LoggingService;

class SendingEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $sendToEmail;
    protected string $bladeName;
    protected string $title;
    protected ?string $filePath;
    protected array $dataForTemplate;
    public int $tries = 3;

    /**
     * SendMessage constructor.
     * @param string $sendToEmail
     * @param string $bladeName
     * @param array $dataForTemplate
     * @param string $title
     * @param ?string $filePath
     */
    public function __construct(string $sendToEmail, string $bladeName, array $dataForTemplate, string $title, string $filePath = null)
    {
        $this->sendToEmail = $sendToEmail;
        $this->bladeName = $bladeName;
        $this->dataForTemplate = $dataForTemplate;
        $this->title = $title;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        try {
            Mail::to($this->sendToEmail)->send(
                new EmailService(
                    $this->bladeName,
                    $this->dataForTemplate,
                    $this->title,
                    $this->filePath
                )
            );
        } catch (\Exception $e) {
            LoggingService::critical('E-mail not sent - ' . $e->getMessage());
            SendingToTelegramService::sendMessage([
                'ERROR' => 'Error in SendingEmailJob',
                'RECIPIENT_EMAIL' => $this->sendToEmail,
                'EMAIL_TITLE' => $this->title,
                'EMAIL_TEMPLATE' => $this->bladeName,
            ]);

            throw $e;
        }
    }
}
