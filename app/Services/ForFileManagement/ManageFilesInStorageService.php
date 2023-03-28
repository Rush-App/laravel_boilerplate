<?php

namespace App\Services\ForFileManagement;

use App\Exceptions\CustomHttpException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RushApp\Core\Services\LoggingService;

class ManageFilesInStorageService
{
    /**
     * @return string
     */
    public static function getUniqueName(): string
    {
        return uniqid();
    }

  /**
   * @param $file
   * @param string $pathForSaving
   * @param string $filesystemDiskName
   * @param string|null $fileName
   * @return string
   */
    public static function saveFile($file, string $pathForSaving, string $filesystemDiskName = 'public', string $fileName = null): string
    {
        $resultFileName = !empty($fileName) ? $fileName : self::getUniqueName();
        $resultFileName = $resultFileName.'.'.$file->extension();

        try {
            $storagePath = Storage::disk($filesystemDiskName)->put($pathForSaving, $file);
            $storageName = basename($storagePath);
            //rename file
            File::move(public_path($pathForSaving.'/'.$storageName), public_path($pathForSaving.'/'.$resultFileName));
        } catch (\Exception $e) {
            LoggingService::critical('Error while saving file '.$file.' - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.save_file_error'));
        }

        return $resultFileName;
    }

    /**
     * @param string $path
     * @return void
     */
    public static function deleteDirectoryWithFiles(string $path): void
    {
        try {
            File::deleteDirectory($path);
        } catch (\Exception $e) {
            LoggingService::critical('Error while deleting directory '.$path.' - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.delete_file_error'));
        }
    }

    /**
     * @param string $pathToFile
     * @return void
     */
    public static function deleteFile(string $pathToFile): void
    {
        try {
            unlink($pathToFile);
        } catch (\Exception $e) {
            LoggingService::critical('Error while deleting file '.$pathToFile.' - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.delete_file_error'));
        }
    }

    public static function createDirectoryIfItDoesntExist(string $path): void
    {
        if (!self::checkingIfDirectoryExists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
    }

    public static function checkingIfDirectoryExists(string $path)
    {
        return File::isDirectory($path);
    }
}
