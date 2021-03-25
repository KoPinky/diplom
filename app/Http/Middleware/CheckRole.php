<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$role_id
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$role_id)
    {
        $user = $request->user();
        if (!$user->hasRole(...$role_id)) {
            return response()->json(
                ['message' => 'Вы не имеете доступ к данному сервису.']);
        }
        return $next($request);
    }
}
