<?php

namespace App\Services\ForSendingMessages;

use App\Jobs\SendingEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SendingEmailService
{
    /**
     * @param string $language
     * @param string $textKey
     * @param string $title
     * @param ?string $filePath
     * @param ?string $email
     * @param string $bladeName
     * @param array $data
     * @return void
     */
    public static function sendEmailForUser
    (
        string $language, string $textKey, string $title, string $filePath = null, string $email = null,
        string $bladeName = 'emails.main.main-', array $data = []
    ): void
    {
        $user = User::find(Auth::id());

        if (!empty($email)) {
            $sendToEmail = $email;
        } elseif (!empty($user)) {
            $sendToEmail = $user->email;
        } else {
            $sendToEmail = null;
        }

        $baseDataForTemplate = ['user' => $user, 'text' => __($textKey, locale: $language)];
        $dataForTemplate = $data ? array_merge($baseDataForTemplate, $data) : $baseDataForTemplate;

        if (!empty($sendToEmail)) {
            dispatch(
                new SendingEmailJob($sendToEmail, $bladeName.$language, $dataForTemplate, __($title, locale: $language), $filePath)
            );
        }
    }

    /**
     * @param string $name
     * @param string $message
     * @param string $email
     * @param ?string $filePath
     * @return void
     */
    public static function sendEmailForAdmin(string $name, string $message, string $email, string $filePath = null): void
    {
        $adminEmail = Config::get('constants.env.admin_email');
        $emailTitle = 'From ' . Config::get('constants.env.mail_from_name');
        $dataForTemplate = ['name' => $name, 'text' => $message, 'email' => $email];

        dispatch(
            new SendingEmailJob($adminEmail, 'emails.to-admin', $dataForTemplate, $emailTitle, $filePath)
        );
    }
}


