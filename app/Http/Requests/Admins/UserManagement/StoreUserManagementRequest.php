<?php

namespace App\Http\Requests\Admins\UserManagement;

use App\Http\Requests\BaseApiRequest;

class StoreUserManagementRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|max:30|confirmed',
            'user_roles_ids' => 'required',
        ];
    }
}
