<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function __construct(protected UserRepository $users)
    {
    }

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $id = auth()->id();
        if ($id === null) {
            return response()->json(["message" => "Unauthorized user"], 401);
        }

        $user = $this->users->find($id);
        if ($user->status !== "active") {
            return response()->json(
                ["message" => "Invalid status " . $user->status],
                401
            );
        }

        if (in_array($user->role, $roles) === false) {
            return response()->json(
                ["message" => "Invalid role " . $user->role],
                401
            );
        }

        return $next($request);
    }
}
