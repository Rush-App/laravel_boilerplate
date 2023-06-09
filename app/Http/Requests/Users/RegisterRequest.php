<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseApiRequest;

class RegisterRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|max:30|confirmed',
            'usage_policy' => 'required|accepted'
        ];
    }
}
