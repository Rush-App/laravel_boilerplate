<?php

namespace App\Models;

use App\Exceptions\CustomHttpException;
use App\Services\ForFileManagement\ImageResizeService;
use App\Services\ForFileManagement\ManageFilesInStorageService;
use Database\Factories\UserFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use RushApp\Core\Services\LoggingService;
use RushApp\Core\Models\User as BaseUser;

class User extends BaseUser
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'usage_policy', 'password', 'email', 'is_deleted'];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function updatePersonalData(Request $request)
    {
        try {
            return tap(User::find(Auth::id()))->update($request->only(['name']));
        } catch (\Exception $e) {
            LoggingService::critical('Cant update updatePersonalData - '.$e->getMessage());
            throw new CustomHttpException(409, __('response_messages.edit_error'));
        }
    }

    public function updateAvatar(UploadedFile $userAvatarFile): string
    {
        $avatarStoragePath = Config::get('constants.private_paths.user_avatars');
        $avatarFileName = ImageResizeService::imageResizeAndSave($userAvatarFile, storage_path($avatarStoragePath), 60, 60);

        $user = User::find(Auth::id());
        if (!empty($user->avatar)) {
            ManageFilesInStorageService::deleteFile(storage_path($avatarStoragePath.'/'.$user->avatar));
        }

        try {
            $user->update([
                'storage_folder_path' => Config::get('constants.private_paths.user_avatars'),
                'avatar' => $avatarFileName,
            ]);
            return __('response_messages.user_avatar_updated');
        } catch (\Exception $e) {
            LoggingService::critical('Cant update user avatar - '.$e->getMessage());
            throw new CustomHttpException(409, __('response_messages.edit_error'));
        }
    }
}
