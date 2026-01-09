<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

            $remember = $request->has('remember');
            Auth::login($user, $remember);


            //Redirect to next page
            if ($user->user_type === 'STU') {
                return redirect()->intended('dashboard')->with('success', 'Successfully logged in!');
            } else if ($user->user_type === 'STA' || $user->user_type === 'ADM') {
                return redirect()->intended(route('staff.dashboard'))->with('success', 'Successfully logged in!');
            }
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['email' => 'Login failed'])->onlyInput($request->only('email'));
        }
    }




    public function staffLogout(Request $request)
    {

        if (Auth::check()) {
            Auth::logout();
        }


        $request->session()->invalidate();
        $request->session()->regenerateToken(); //Generates new csrf token


        return redirect()->route('staff.login.form')->with('success', 'Successfully logged out');
    }


    public function studentLogout(Request $request)
    {

        if (Auth::check()) {
            Auth::logout();
        }


        $request->session()->invalidate();
        $request->session()->regenerateToken(); //Generates new csrf token

        return redirect()->route('login.form')->with('success', 'Successfully logged out');
    }

    public function forgotPassword(Request $request, SmsService $sms)
    {
        //Get user email
        $emailData = $request->validate(['email' => 'email|required']);

        //Check if user email is in db
        try {
            $user = TblUser::where('email', $emailData['email'])->first();

            if (!$user) {
                return back()->withErrors(['email' => 'User not found'])->withInput();
            }

            $otp = rand(100000, 999999);
            
            // Store OTP in cache with email
            Cache::put('otp:' . $user->email, [
                'otp' => $otp,
                'email' => $user->email
            ], now()->addMinutes(10)); // OTP expires in 10 minutes
            
            $message = "Below is your code for verification\n{$otp}";

            $phone = preg_replace('/^0/', '233', $user->phone);
            $sms->send($phone, $message);

            // Store email in session for OTP verification page
            session(['reset_email' => $user->email]);

            return redirect()->route('verifyOTP.form')->with('status', 'Verification code sent to your phone');
        } catch (\Exception $e) {
            Log::error('Password reset failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['email' => 'Failed to send verification code. Please try again.'])->withInput();
        }
    }

    
    public function verifyOTP(Request $request)
    {
        $otp = $request->input('otp');
        $email = session('reset_email') ?? $request->input('email');

        $request->merge(['otp' => $otp, 'email' => $email]);

        $fields = $request->validate([
            'otp' => 'numeric|required|digits:6',
            'email' => 'email|required',
            'system' => 'nullable|string'
        ]);

        $userOtp = $fields['otp'];
        $userEmail = $fields['email'];
        $system = $fields['system'] ?? 'reset';

        $storedOtpData = Cache::get('otp:' . $userEmail);
        $storedOtp = $storedOtpData['otp'] ?? null;

        if ($storedOtp && $storedOtp == $userOtp) {
            Cache::forget("otp:" . $userEmail);
            $user = TblUser::where('email', $userEmail)->firstOrFail();

            if ($system == 'login') {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            } else {
                // Password reset flow - redirect to reset password page
                Auth::login($user, true); // Login temporarily to allow password reset
                session(['password_reset_verified' => true]);
                return redirect()->route('password.reset')->with('success', 'OTP verified successfully. Please set your new password.');
            }
        } else {
            return back()->withErrors(['otp' => 'Invalid or expired verification code'])->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        $fields = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $request->user()->update([
            'password' => Hash::make($fields['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    // Add after the forgotPassword method (around line 120)

public function showForgotPassword()
{
    return view('forgotPassword');
}

public function showVerifyOTP(Request $request)
{
    // Get email from session or request
    $email = session('reset_email') ?? $request->input('email', 'your email');
    
    // Mask the email for display
    $maskedEmail = $this->maskEmail($email);
    
    return view('verifyOtp', compact('maskedEmail', 'email'));
}

public function showResetPassword(Request $request)
{
    // Validate token if using token-based reset
    // For now, we'll assume user is authenticated or has valid session
    return view('resetPassword');
}

public function resetPassword(Request $request)
{
    $fields = $request->validate([
        'old_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();

    // Verify old password
    if (!Hash::check($fields['old_password'], $user->password)) {
        return back()->withErrors(['old_password' => 'Current password is incorrect'])->withInput();
    }

    // Update password
    $user->update([
        'password' => Hash::make($fields['new_password'])
    ]);

    return redirect()->route('login.form')->with('success', 'Password reset successfully! Please login with your new password.');
}

private function maskEmail($email)
{
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'your email';
    }
    
    $parts = explode('@', $email);
    $username = $parts[0];
    $domain = $parts[1] ?? '';
    
    if (strlen($username) <= 2) {
        $maskedUsername = str_repeat('*', strlen($username));
    } else {
        $maskedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);
    }
    
    return $maskedUsername . '@' . $domain;
}
}
