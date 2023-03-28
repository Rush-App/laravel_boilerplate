<?php

namespace App\Http\Requests\Admins;

use App\Http\Requests\BaseApiRequest;

class CssVariableCategoryRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:100',
        ];
    }
}
