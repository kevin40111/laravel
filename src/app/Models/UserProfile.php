<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';
    protected $primaryKey = 'id';

    protected $fillable = [
        'billing',
        'fullName',
        'company',
        'role',
        'username',
        'country',
        'contact',
        'email',
        'currentPlan',
        'status',
        'avatar',
        'avatarColor',
    ];
}
