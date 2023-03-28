<?php

namespace App\Models\CoreModels;

use RushApp\Core\Models\BaseModel;

class Role extends BaseModel
{
    protected $fillable = ['name', 'is_registration_role', 'description'];

    public $timestamps = false;
}
