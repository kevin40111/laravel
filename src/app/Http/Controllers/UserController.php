<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function __construct(protected UserRepository $users)
    {
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        $id = auth()->id();
        $user = $this->users->find($id);
        if (is_null($user)) {
            return response()->json(["message" => "User not found"], 401);
        }

        if ($user->status !== "active") {
            return response()->json(
                ["message" => "User is not activated"],
                401
            );
        }

        return response()->json($user);
    }

    public function getUsers(Request $request)
    {
        $validator = Validator::make($request->query(), [
            "page" => "required|integer",
            "size" => "required|integer|between:2,10",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $validated = $validator->validated();

        $profiles = $this->users->fetchItems(
            $validated["page"],
            $validated["size"]
        );
        $count = $this->users->fetchItemsCount();
        $totalPages = ceil($count / $validated["size"]);

        return response()->json([
            "content" => $profiles,
            "pageable" => [
                "pageNumber" => $validated["page"],
                "pageSize" => $validated["size"],
                "totalPages" => $totalPages,
            ],
        ]);
    }

    public function getUser(Request $request)
    {
        $id = $request->route("id");
        $user = $this->users->find($id);

        return response()->json($user);
    }

    public function updateUser(Request $request)
    {
        $id = $request->route("id");
        $validator = Validator::make($request->post(), [
            "username" => "required|string|between:2,100",
            "fullName" => "required|string",
            "status" => "required|string",
            "role" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $validated = $validator->validated();
        $user = $this->users->update($id, $validated);

        return response()->json($user);
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
