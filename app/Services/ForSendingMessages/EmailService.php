<?php

namespace App\Services\ForSendingMessages;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailService extends Mailable
{
    use Queueable, SerializesModels;

    private string $blade;
    private string $title;
    private ?string $filePath;
    private array $data;

    /**
     * MailService constructor.
     * @param string $blade
     * @param string $title
     * @param ?string $filePath
     * @param array $data
     */
    public function __construct(string $blade, array $data, string $title, string $filePath = null)
    {
        $this->blade = $blade;
        $this->data = $data;
        $this->title = $title;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailData = $this->view($this->blade)->subject(__($this->title))->with($this->data);

        if(!empty($this->filePath)) {
            $mailData->attach($this->filePath);
        }

        return $mailData;
    }
}
