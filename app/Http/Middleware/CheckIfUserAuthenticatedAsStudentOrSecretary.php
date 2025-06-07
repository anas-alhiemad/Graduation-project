<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserAuthenticatedAsStudentOrSecretary
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('student')->check() || Auth::guard('secretary')->check() || Auth::guard('trainer')->check()) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Unauthorized. Make sure you are logged in,Please log in.'
        ], 401);
    }
}
