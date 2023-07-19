<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\UserProfile;

class UserRegister extends Model
{
    protected $table = "user_register";

    public $timestamps = true;

    protected $fillable = ["username", "email", "password", "token", "used_at"];

    protected $hidden = ["password", "token"];

    public function user_profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, "user_id");
    }
}
