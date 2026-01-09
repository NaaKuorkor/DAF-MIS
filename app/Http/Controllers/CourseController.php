<?php

namespace App\Http\Controllers;

use App\Models\TblCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function viewCourses()
    {
        try {
            $courses = TblCourse::where('deleted', '0')
                ->orderBy('createdate', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load courses', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load courses'
            ], 500);
        }
    }

    public function createCourse(Request $request)
    {
        try {
            $fields = $request->validate([
                'course_id' => 'required|string|unique:tblcourse,course_id',
                'course_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'eligibility' => 'nullable|string',
                'duration' => 'nullable|string',
            ]);

            TblCourse::create([
                'course_id' => $fields['course_id'],
                'course_name' => $fields['course_name'],
                'description' => $fields['description'] ?? null,
                'eligibility' => $fields['eligibility'] ?? null,
                'duration' => $fields['duration'] ?? null,
                'deleted' => '0',
                'createuser' => auth()->user()->email,
                'createdate' => now(),
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Course creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course creation failed'
            ], 500);
        }
    }

    public function searchCourse(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            $courses = TblCourse::where('deleted', '0')
                ->where(function($q) use ($query) {
                    $q->where('course_name', 'like', '%' . $query . '%')
                      ->orWhere('course_id', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%');
                })
                ->orderBy('createdate', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            Log::error('Course search failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course search failed'
            ], 500);
        }
    }

    public function filterCourses(Request $request)
    {
        try {
            $query = TblCourse::where('deleted', '0');

            // Add any filter logic here based on request parameters
            if ($request->filled('filter')) {
                // Implement filtering logic as needed
            }

            $courses = $query->orderBy('createdate', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            Log::error('Course filter failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course filter failed'
            ], 500);
        }
    }

    public function getCourse($course_id)
    {
        try {
            $course = TblCourse::where('course_id', $course_id)
                ->where('deleted', '0')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $course
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load course', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
    }

    public function updateCourse(Request $request, $course_id)
    {
        try {
            $course = TblCourse::where('course_id', $course_id)
                ->where('deleted', '0')
                ->firstOrFail();

            $fields = $request->validate([
                'course_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'eligibility' => 'nullable|string',
                'duration' => 'nullable|string',
            ]);

            $course->update([
                'course_name' => $fields['course_name'],
                'description' => $fields['description'] ?? null,
                'eligibility' => $fields['eligibility'] ?? null,
                'duration' => $fields['duration'] ?? null,
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Course update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course update failed'
            ], 500);
        }
    }

    public function deleteCourse($course_id)
    {
        try {
            $course = TblCourse::where('course_id', $course_id)
                ->where('deleted', '0')
                ->firstOrFail();

            $course->update([
                'deleted' => '1',
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Course deletion failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course deletion failed'
            ], 500);
        }
    }
}