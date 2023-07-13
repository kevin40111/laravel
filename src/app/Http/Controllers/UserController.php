<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
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

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        $users = User::all();
        return response()->json($users);
    }

    public function getUserProfile(Request $request)
    {
        // 取得目前已認證的使用者
        $user = Auth::user();

        if ($user === null) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        $user_by_id = UserProfile::find($request->route('id'));
        return response()->json($user_by_id);
    }
}
