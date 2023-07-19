<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\UserRegister;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = "user_profile";
    protected $primaryKey = "id";

    protected $fillable = [
        "id",
        "billing",
        "fullName",
        "company",
        "role",
        "username",
        "country",
        "contact",
        "email",
        "currentPlan",
        "status",
        "avatar",
        "avatarColor",
    ];

    public function user_register(): BelongsTo
    {
        return $this->belongsTo(UserRegister::class, "id");
    }
}
