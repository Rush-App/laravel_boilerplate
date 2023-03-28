<?php

namespace App\Http\Requests\Admins\UserManagement;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class UpdateUserManagementRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore(request()->id)
            ],
            'user_roles_ids' => 'required',
        ];
    }
}
