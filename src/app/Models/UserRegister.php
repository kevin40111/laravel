<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegister extends Model
{
    protected $table = "user_register";

    public $timestamps = true;

    protected $fillable = ["username", "email", "password", "token", "used_at"];

    protected $hidden = ["password", "token"];
}
