<?php

namespace App\Services\ForFileManagement;

use App\Exceptions\CustomHttpException;
use Intervention\Image\ImageManager;
use RushApp\Core\Services\LoggingService;

class ImageResizeService
{
    public static function imageResizeAndSave($uploadedImage, string $pathForSaving, int $width, int $height, string $fileName = null): string
    {
        $format = 'png';
        $resultFileName = !empty($fileName) ? $fileName : ManageFilesInStorageService::getUniqueName();
        $resultFileName = $resultFileName.'.'.$format;

        ManageFilesInStorageService::createDirectoryIfItDoesntExist($pathForSaving);

        try {
            $image = (new ImageManager('gd'))->make($uploadedImage);
            $image->resize($width, $height)->toPng()->save($pathForSaving.'/'.$resultFileName);
        } catch (\Exception $e) {
            LoggingService::critical('Error while saving file '.$uploadedImage.' - ' . $e->getMessage());
            throw new CustomHttpException(409, __('response_messages.save_file_error'));
        }

        return $resultFileName;
    }
}
