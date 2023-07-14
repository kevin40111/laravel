<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * 儲存更換後的密碼
     */
    public function changePassword(Request $request)
    {
        // 驗證輸入
        $request->validate([
            "current_password" => "required",
            "new_password" => "required|string|min:8",
        ]);

        // 取得目前已認證的使用者
        $user = Auth::user();

        if ($user === null) {
            return response()->json(["message" => "Unauthorized user"], 401);
        }

        // 驗證目前密碼是否正確
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(["message" => trans("auth.failed")], 401);
        }

        // 更新密碼
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(
            ["message" => "Password successfully changed."],
            200
        );
    }
}
