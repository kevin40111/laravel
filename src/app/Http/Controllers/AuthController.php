<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRegister;
use App\Models\ResetCodePassword;
use App\Mail\RegisterMail;
use App\Mail\ResetPasswordMail;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function __construct(protected UserRepository $users)
    {
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->post(), [
            "email" => "required|email",
            "password" => "required|string|min:5",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();
        $user = $this->users->findByEmail($validated["email"]);
        if (is_null($user)) {
            return response()->json(["message" => "User not found"], 401);
        }

        $token = auth()->attempt($validated);
        if (is_bool($token) && $token == false) {
            return response()->json(["message" => "Password incorrect"], 401);
        }

        $userData = auth()->user();

        $response = [
            "accessToken" => $token,
            "userData" => $userData,
        ];

        if ($response) {
            return response()->json($response, 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->post(), [
            "username" => "required|string|between:2,100",
            "email" => "required|string|email|max:100",
            "password" => "required|string|min:6",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $validated = $validator->validated();
        $user = $this->users->findByEmail($validated["email"]);
        if (is_null($user) == false) {
            return response()->json(
                ["message" => "User already registered"],
                401
            );
        }

        $data = array_merge(
            $validator->validated(),
            ["password" => bcrypt($request->password)],
            ["token" => Uuid::uuid4()->toString()]
        );

        $register = UserRegister::create($data);
        Mail::to($register)->send(new RegisterMail($register));

        return response()->json($register, 201);
    }

    public function registerResend(Request $request)
    {
        $validator = Validator::make($request->post(), [
            "email" => "required|string|email|max:100",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $validated = $validator->validated();
        $user = $this->users->findByEmail($validated["email"]);
        if (is_null($user) == false) {
            return response()->json(
                [
                    "message" => "User already registered",
                ],
                401
            );
        }

        $register = UserRegister::where("email", $validated["email"])->first();
        if (is_null($register)) {
            return response()->json(
                [
                    "message" => "Register record not found",
                ],
                401
            );
        }

        Mail::to($register)->send(new RegisterMail($register->token));

        return response()->json($register, 201);
    }

    public function confirm(Request $request)
    {
        $token = $request->route("token");
        $register = UserRegister::where("token", $token)->first();
        if (is_null($register)) {
            return response()->json(["message" => "Invalid token"], 401);
        }

        if (is_null($register->used_at) == false) {
            return response()->json(["message" => "Token is used"], 401);
        }

        $user = $this->users->findByEmail($register->email);
        if (is_null($user) == false) {
            return response()->json(
                [
                    "message" => "User already registered",
                ],
                401
            );
        }

        $user = $this->users->create([
            "username" => $register->username,
            "email" => $register->email,
            "password" => $register->password,
        ]);

        $register->used_at = now();
        $register->save();

        return response()->json($user, 201);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->post(), [
            "email" => "required|email",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();

        $user = $this->users->findByEmail($validated["email"]);
        if (is_null($user)) {
            return response()->json(["message" => "User not found"], 404);
        }

        // Delete all old code that user send before.
        ResetCodePassword::where("email", $validated["email"])->delete();

        $data = array_merge($validated, ["code" => Uuid::uuid4()->toString()]);

        $resetPassword = ResetCodePassword::create($data);

        // Send email to user
        Mail::to($request->email)->send(new ResetPasswordMail($resetPassword));

        return response(["message" => trans("passwords.sent")], 200);
    }

    public function getEmailByCode(Request $request)
    {
        $code = $request->route("code");

        // find the code
        $resetPasswordRequest = ResetCodePassword::where(
            "code",
            $code
        )->first();
        if (is_null($resetPasswordRequest)) {
            return response(["message" => "Invalid reset password code"], 404);
        }

        // check if it does not expired: the time is one hour
        if ($resetPasswordRequest->created_at->addHour()->isPast()) {
            return response(
                ["message" => trans("passwords.code_is_expire")],
                422
            );
        }

        return response()->json($resetPasswordRequest, 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            "code" => "required|string|exists:reset_code_passwords",
            "password" => "required|string|min:6",
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere("code", $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at->addHour()->isPast()) {
            return response(
                ["message" => trans("passwords.code_is_expire")],
                422
            );
        }

        // find user's email
        $user = $this->users->findByEmail($passwordReset->email);
        if (is_null($user)) {
            return response(["message" => "User not found"], 404);
        }

        // Retrieve the password from the request
        $password = $request->only("password")["password"];

        // Hash the password
        $hashedPassword = bcrypt($password);

        // Replace the original password value in the request data
        $request->merge(["password" => $hashedPassword]);

        // update user password
        $user->update($request->only("password"));

        // delete current code
        $passwordReset->delete();

        return response(
            ["message" => "password has been successfully reset"],
            200
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(["message" => "User successfully signed out"]);
    }
}
