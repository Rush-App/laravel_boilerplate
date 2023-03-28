<?php

namespace App\Http\Requests\Admins;

use App\Http\Requests\BaseApiRequest;

class CssVariableRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'variable_name' => 'required|max:50',
            'variable_value' => 'required|max:50',
            'css_variable_category_id' => 'required',
            'variable_default_value' => 'prohibited',
        ];
    }
}
