<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseApiRequest;

class UserAvatarRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'user_avatar_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
