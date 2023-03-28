<?php

namespace App\Http\Requests\Admins\AccessLevelControl;

use App\Http\Requests\BaseApiRequest;

class StoreAccessLevelControlRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'action_name' => 'required|max:255|unique:actions,name',
            'role_id' => 'required',
        ];
    }
}
