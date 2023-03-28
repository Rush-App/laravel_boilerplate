<?php

namespace App\Models\CoreModels;

use RushApp\Core\Models\BaseModel;

class Action extends BaseModel
{
    protected $fillable = ['name'];

    public $timestamps = false;
}
