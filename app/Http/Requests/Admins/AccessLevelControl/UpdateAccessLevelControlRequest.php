<?php

namespace App\Http\Requests\Admins\AccessLevelControl;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class UpdateAccessLevelControlRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'is_owner' => 'required',
            'action_id' => 'prohibited',
            'excluded_fields' => 'prohibited',
            'id' => 'prohibited',
            'is_owner_key' => 'prohibited',
            'role_id' => 'prohibited',
        ];
    }
}
