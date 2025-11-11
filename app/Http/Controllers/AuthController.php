<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function login(Request $request)
    {

        $userData = $request->validate([
            'email' => "email|required",
            'password' => "required",
        ]);


        //Check if it details are in Db
        try {
            $user = TblUser::where('email', $userData['email'])->first();

            if (!$user) {
                return back()->withErrors(['email' => 'User not found'])->withInput();
            }

            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Incorrect Password!'])->withInput();
            }

            $guard = null;

            //Log user in
            if ($user->user_type === 'STU') {
                $guard = 'student';
            } else if ($user->user_type === 'STA' || $user->user_type === 'ADM') {
                $guard = 'staff';
            }

            $remember = $request->has('remember');
            Auth::guard($guard)->login($user, $remember);


            //Redirect to next page
            if ($guard === 'student') {
                return redirect()->intended('dashboard');
            } else if ($guard === 'staff') {
                return redirect()->intended(route('staff.dashboard'));
            }
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['email' => 'Login failed'])->onlyInput($request->only('email'));
        }
    }



    public function apiLogin(Request $request)
    {
        $userData = $request->validate([
            'email' => "email|required",
            'password' => "required",
        ]);

        try {
            $user = TblUser::where('email', $userData['email'])->first();

            if (!$user) {
                return response()->json([
                    'ok' => false,
                    'message' => 'User not found!'
                ], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return  response()->json([
                    'ok' => false,
                    'message' => 'Incorrect Password'
                ], 401);
            }

            $guard = null;

            //Log user in
            if ($user->user_type === 'STU') {
                $guard = 'student';
            } else if ($user->user_type === 'STA') {
                $guard = 'staff';
            }

            $remember = $request->has('remember');
            Auth::guard($guard)->login($user, $remember);
        } catch (\Exception $e) {
            Log::error("Login failed", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Login failed'
            ], 401);
        }
    }



    public function logout(Request $request, $guard)
    {
        if (!in_array($guard, ['student', 'staff'])) {
            return redirect()->route('login.form')->with('error', 'Invalid logout request');
        }

        if (Auth::guard($guard)->check()) {
            Auth::guard($guard)->logout();
        }


        $request->session()->invalidate();
        $request->session()->regenerateToken(); //Generates new csrf token


        if ($guard === 'student') {
            return redirect()->route('login.form')->with('success', 'Successfully logged out!');
        }
        if ($guard === 'staff') {
            return redirect()->route('staff.login.form')->with('success', 'Successfully logged out!');
        }

        return redirect()->route('login.form')->with('success', 'Successfully logged out');
    }
}
