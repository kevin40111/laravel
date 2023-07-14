<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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

    public function updateUserProfile(Request $request)
    {
        // 取得目前已認證的使用者
        $user = Auth::user();

        if ($user === null) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        // if ($user->role !== 'admin') {
        //     return response()->json(['message' => 'Permission denied'], 403);
        // }

        $user = UserProfile::find($request->route('id'));

        $data = $request->post();

        // Validate the request data todo list
        //  $validatedData = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        //     // Additional validation rules for other fields
        // ]);

        // Update the user
        $user->update($data);


        return response()->json(['message' => 'Updated successfully', 'user' => $user, 'data'=>$data]);
    }

    public function updateUserRole(Request $request)
    {
        // 取得目前已認證的使用者
        $user = Auth::user();

        if ($user === null) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        // Validate the request data todo list
        $validator = Validator::make($request->post(), [
            'role' => 'required|string|between:2,10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = UserProfile::find($request->route('id'));

        $data = $request->post();

        // Update the user
        $user->update($data);


        return response()->json(['message' => 'Updated successfully', 'user' => $user, 'data'=>$data]);
    }
}
