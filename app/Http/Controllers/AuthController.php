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
        $email = $request->validate(['email' => 'email||required']);

        //CHeck if user email is in db
        try {
            $email = TblUser::where('email', $email['email'])->first();

            if (!$email) {
                return back()->withErrors(['email' => 'User not found'])->withInput();
            }

            $otp = rand(100000, 999999);
            $message = `Below is your code for verification\n
            {$otp}`;

            $phone = preg_replace('/^0/', '233', $request->user->phone);

            $sms->send($phone, $message);

            return redirect('/verifyOTP');
        } catch (\Exception $e) {
            Log::error('Failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function verifyOTP(Request $request)
    {
        $otp = $request->input('otp');

        $request->merge(['otp' => $otp]);

        $fields = $request->validate([

            'otp' => 'numeric|required|digits:6',

            'system' => 'required|string'

        ]);

        // dd($request);

        $userOtp = $fields['otp'];

        $email = $fields['email'];

        $storedOtp = Cache::get('otp:' . $email)['otp'] ?? null;



        if ($storedOtp && $storedOtp == $userOtp) {

            Cache::forget("otp:" . $email);
            $user = TblUser::where('email', $email)->first();

            if ($fields['system'] == 'login') {

                Auth::login($user);

                $request->session()->regenerate();

                return redirect();
            }
        } else {
            return redirect();
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

        return response()->json(['Password updated successfully']);
    }
}
