<?php

namespace App\Models\CoreModels;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RushApp\Core\Models\BaseModel;

class UserRole extends BaseModel
{
    public $table = 'user_role';

    protected $fillable = ['user_id', 'role_id'];

    public $timestamps = false;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
