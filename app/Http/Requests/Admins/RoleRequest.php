<?php

namespace App\Http\Requests\Admins;

use App\Http\Requests\BaseApiRequest;

class RoleRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'is_registration_role' => 'required',
            'description' => 'nullable|max:255',
        ];
    }
}
