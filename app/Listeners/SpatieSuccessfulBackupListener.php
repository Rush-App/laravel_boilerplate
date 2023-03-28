<?php

namespace App\Listeners;

use App\Services\ForSendingMessages\SendingEmailService;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Events\BackupWasSuccessful;

class SpatieSuccessfulBackupListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BackupWasSuccessful  $event
     * @return void
     */
    public function handle(BackupWasSuccessful $event)
    {
        $this->sendingEmailWithBackupFile($event->backupDestination);
    }

    public function sendingEmailWithBackupFile(BackupDestination $backupDestination): void
    {
        $filePath = storage_path('app/').$backupDestination->newestBackup()->path();

        SendingEmailService::sendEmailForAdmin(
            'Spatie Backup',
            'auto-backup done',
            'no-reply',
            $filePath,
        );
    }
}
