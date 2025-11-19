<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class Staff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        //Check if user is authenticated then send data
        if ($user) {
            View::share(
                'userData',
                [
                    'email' => $user->email,
                    'name' => $user->fname . ' ' . $user->lname,
                    'logout' => 'staff.logout'
                ]
            );
        }

        return $next($request);
    }
}
