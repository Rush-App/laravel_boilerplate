<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseApiRequest;

class MessageRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|max:50',
            'name' => 'required|max:20',
            'message' => 'nullable|max:255',
        ];
    }
}
