<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserList()
    {
        // 取得目前已認證的使用者
        $user = Auth::user();

        if ($user === null) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        if ($user->role !== 'admin'){
            return response()->json(['message' => 'Permission denied'], 403);
        }

        $users = User::all();
        return response()->json($users);
    }
}
