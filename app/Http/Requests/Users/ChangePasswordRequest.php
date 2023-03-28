<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseApiRequest;

class ChangePasswordRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'old_password' => 'required|max:30',
            'password' => 'required|max:30|confirmed',
        ];
    }
}
