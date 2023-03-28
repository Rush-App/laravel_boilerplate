<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseApiRequest;

class UserRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:100',
        ];
    }
}
