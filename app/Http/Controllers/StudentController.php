<?php

namespace App\Http\Controllers;

use App\Models\TblCourseRegistration;
use Illuminate\Http\Request;
use App\Models\TblUser;
use App\Models\TblStudent;
use App\Models\TblUserModulePriviledges;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\Verify;
use App\Services\SmsService;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function register(Request $request, SmsService $sms)
    {
        // Log the request to help debug
        Log::info('Student registration attempt', [
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'has_auth' => auth()->check(),
            'user_type' => auth()->check() ? auth()->user()->user_type : 'guest',
            'request_data_keys' => array_keys($request->all())
        ]);

        try {
            //Form validation
            $validateData = $request->validate([
                'fname' => 'required|string|max:255',
                'mname' => 'nullable|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:tbluser,email',
                'phone' => 'required|string|max:15',
                'password' => 'required|string|min:8|confirmed',
                'gender' => 'required|string|in:M,F',
                'age' => 'required|integer|min:1|max:120',
                'residence' => 'required|string|max:255',
                'referral' => 'required|string',
                'employment_status' => 'required|string',
                'certificate' => 'required|string|in:Y,N',
                'course' => 'required|string|exists:tblcourse,course_id'
            ]);

            $password = Hash::make($validateData['password']);

            //Create student to be inserted into the databases

            DB::transaction(function () use ($validateData, &$user, &$student, $password) {


                $idCount =  DB::table('tbluser')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');

                $userid = null;

                if ($idCount === 0) {
                    $userid = 'U0000000001';
                } else {
                    $newNum = $idCount + 1;
                    $userid = 'U' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }




                $studentCount =  DB::table('tblstudent')->selectRaw('COUNT(*) as count')->lockForUpdate()->value('count');

                $studentid = null;

                if ($studentCount === 0) {
                    $studentid = 'STU0000000001';
                } else {
                    $newNum = $studentCount + 1;
                    $studentid = 'STU' . str_pad($newNum, 10, '0', STR_PAD_LEFT);
                }


                $user = TblUser::create([
                    'userid' => $userid,
                    'email' => $validateData['email'],
                    'phone' => $validateData['phone'],
                    'password' => $password,
                    'user_type' => 'STU',
                    'createuser' => $validateData['email'],
                    'modifyuser' => $validateData['email'],
                ]);




                $student = TblStudent::create([
                    'userid' => $user->userid,
                    'studentid' => $studentid,
                    'fname' => $validateData['fname'],
                    'mname' => $validateData['mname'] ?? null,
                    'lname' => $validateData['lname'],
                    'gender' => $validateData['gender'],
                    'age' => $validateData['age'],
                    'residence' => $validateData['residence'],
                    'referral' => $validateData['referral'],
                    'employment_status' => $validateData['employment_status'],
                    'certificate' => $validateData['certificate'],
                ]);

                TblCourseRegistration::create([
                    'studentid' => $student->studentid,
                    'course_id' => $validateData['course'],
                    'createuser' => $validateData['email'],
                    'modifyuser' => $validateData['email'],
                ]);


                $modulePriviledges = [
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD012', // Courses and Cohorts
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 0,
                        'mod_delete' => 0,
                    ],
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD013', // Announcements
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,
                    ],
                    [
                        'userid' => $user->userid,
                        'modid' => 'MOD014', // Profile
                        'mod_create' => 0,
                        'mod_read' => 1,
                        'mod_update' => 1,
                        'mod_delete' => 0,
                    ]
                ];

                foreach ($modulePriviledges as $p) {
                    TblUserModulePriviledges::create([
                        'userid' => $p['userid'],
                        'modid' => $p['modid'],
                        'mod_create' => $p['mod_create'],
                        'mod_read' => $p['mod_read'],
                        'mod_update' => $p['mod_update'],
                        'mod_delete' => $p['mod_delete'],
                        'createuser' => 'system',
                        'modifyuser' => 'system',
                    ]);
                }
            });

            //Create verification link to be sent to user
            $verificationLink = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->userid, 'hash' => sha1($user->email)]
            );

            //Send mail with verification link.
            try {
                Mail::to($user->email)->send(new Verify($student, $verificationLink));
                Log::info('Verification email sent', ['userid' => $user->userid, 'email' => $user->email]);
            } catch (\Exception $mailError) {
                Log::error('Failed to send verification email', [
                    'userid' => $user->userid,
                    'email' => $user->email,
                    'error' => $mailError->getMessage()
                ]);
                // Don't fail registration if email fails, but log it
            }

            // Always return JSON for API requests (ajax, wantsJson, or expectsJson)
            // Also check Accept header to determine response type
            $acceptsJson = $request->ajax() || $request->wantsJson() || $request->expectsJson() || 
                          str_contains($request->header('Accept', ''), 'application/json');
            
            Log::info('Student registration successful', [
                'userid' => $user->userid,
                'email' => $user->email,
                'accepts_json' => $acceptsJson
            ]);
            
            if ($acceptsJson) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! Please check your email for verification link.'
                ]);
            } else {
                return redirect('/email/verify')->with('success', 'Registration successful! Please check your email for verification link.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            Log::warning('Student registration validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            // Always return JSON for AJAX/API requests
            $acceptsJson = $request->ajax() || $request->wantsJson() || $request->expectsJson() || 
                          str_contains($request->header('Accept', ''), 'application/json');
            
            if ($acceptsJson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed. Please check your input.',
                    'errors' => $e->errors()
                ], 422);
            } else {
                return back()->withErrors($e->errors())->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Student registration failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            // Always return JSON for AJAX/API requests
            $acceptsJson = $request->ajax() || $request->wantsJson() || $request->expectsJson() || 
                          str_contains($request->header('Accept', ''), 'application/json');
            
            if ($acceptsJson) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: "Registration failed. Please try again."
                ], 500);
            } else {
                return back()->withErrors("Registration failed. Please try again.")->withInput();
            }
        }
    }



    public function showVerifySuccess(Request $request, $id, $hash)
    {

        //Retrieves student from database
        $user = TblUser::where('userid', $id)->firstOrFail();

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect('/register')->with('error', 'Invalid verification link.');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect('/login')->with('success', 'Email verified!');
    }
}
