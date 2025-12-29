<?php

namespace App\Http\Controllers;

use App\Models\TblCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{

    public function createCourse(Request $request)
    {
        try {

            $fields = $request->validate([
                'course_name' => 'required|string',
                'description' => 'required|string',
                'eligibility' => 'nullable|string',
                'duration' => 'required|string',
            ]);

            //Generate course id
            $course_id = 'CRS-' . random_int(100, 999);

            TblCourse::create([
                'course_id' => $course_id,
                'course_name' => $fields['course_name'],
                'description' => $fields['description'],
                'eligibility' => $fields['eligibility'],
                'duration' => $fields['duration'],
                'deleted' => '0',
                'createuser' => auth()->user()->email,
                'modifyuser' => auth()->user()->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Creation unsuccessful', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course creation failed'
            ]);
        }
    }

    public function viewCourses()
    {

        $courses = TblCourse::where('deleted', '0')
            ->orderBy('createdate', 'desc')
            ->paginate(12);

        //Transform them later

        return response()->json($courses);
    }

    public function getCourse($course_id){

        $course = TblCourse::where('course_id', $course_id)
            ->where('deleted', '0')
            ->firstOrFail();

        return response()->json($course);

    }

    public function searchCourse(Request $request){
        $searchQuery = $request->input('q');

        $query = TblCourse::where('deleted', '0');

        if (!empty($searchQuery)) {
            $query->where(function ($builder) use ($searchQuery) {
                $builder->where('course_name', 'like', '%' . $searchQuery . '%')
                   ->orWhere('description', 'like', '%' . $searchQuery . '%');
            });
        }

        $courses = $query
            ->orderBy('createdate', 'desc')
            ->paginate(12);

        return response()->json($courses);
    }

    public function filterCourses(Request $request)
    {
        $request->validate([
            'duration' => 'nullable|string|max:20',
            'eligibility' => 'nullable|string|max:255',
        ]);

        $query = TblCourse::where('deleted', '0');

        if ($request->filled('duration')) {
            $query->where('duration', $request->duration);
        }

        if ($request->filled('eligibility')) {
            $query->where('eligibility', $request->eligibility);
        }

        $courses = $query->orderBy('createdate', 'desc')
            ->paginate(12);

        return response()->json($courses);
    }

    public function updateCourse(Request $request, $course_id)
    {

        try {
            $fields = $request->validate([
                'course_name' => 'required|string',
                'description' => 'required|string',
                'eligibility' => 'nullable|string',
                'duration' => 'required|string'

            ]);


            TblCourse::where('course_id', $course_id)->update([
                'course_name' => $fields['course_name'],
                'description' => $fields['description'],
                'eligibility' => $fields['eligibility'] ,
                'duration' => $fields['duration'],
                'modifyuser' => auth()->user()->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successful',
            ]);


        } catch (\Exception $e) {
            Log::error('Course update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course update failed'
            ]);
        }
    }

    public function deleteCourse($course_id)
    {
        try {

            TblCourse::where('course_id', $course_id)->update([
                'deleted' => '1',
                'modifyuser' => auth()->user()->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Deletion Unsuccessful', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Course deletion failed'
            ]);
        }
    }
}
