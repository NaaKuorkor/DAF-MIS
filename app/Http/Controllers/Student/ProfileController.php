<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TblStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function showProfile()
    {
        try {
            $student = auth()->user()->student;

            $profile = TblStudent::where('studentid', $student->studentid)
                ->where('deleted', '0')
                ->first();

            return response()->json([
                'success' => true,
                'data' => $profile
            ]);
        } catch (\Exception $e) {
            Log::error('Profile loading failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function updateProfile(Request $request)
    {
        try {

            $user = Auth::user();

            $fields = $request->validate([
                'fname' => 'string|required|max:50',
                'mname' => 'nullable|string|max:50',
                'lname' => 'string|required|max:50',
                'email' => 'email|required',
                'gender' => 'string|required|max:1',
                'age' => 'integer|required',
                'phone' => 'string|required|max:15',
                'residence' => 'required|string',
                'referral' => 'required|string',
                'employment_status' => 'required|string',

            ]);


            DB::transaction(function () use ($user, $fields) {
                //Insert in usertable
                $user->update([
                    'email' => $fields['email'],
                    'phone' => $fields['phone'],
                ]);

                //Insert in staff table
                $user->student->update([
                    'fname' => $fields['fname'],
                    'mname' => $fields['mname'],
                    'lname' => $fields['lname'],
                    'gender' => $fields['gender'],
                    'age' => $fields['age'],
                    'residence' => $fields['residence'],
                    'referral' => $fields['referral'],
                    'employment_status' => $fields['employment_status']
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('Update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Updates failed'
            ]);
        }
    }
}
