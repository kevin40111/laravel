<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param string $email
     * @return null|mixed
     */
    public function findByEmail(string $email)
    {
        return User::where("email", $email)->first();
    }
}
