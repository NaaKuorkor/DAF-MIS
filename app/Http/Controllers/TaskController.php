<?php

namespace App\Http\Controllers;

use App\Models\TblTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {

        try {

            $fields = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'priority' => 'required|string',
            ]);

            $task_id = 'TSK-' . rand(100, 900);

            TblTask::create([
                'task_id' => $task_id,
                'userid' => auth()->user()->userid,
                'title' => $fields['title'],
                'description' => $fields['description'],
                'due_date' => $fields['due_date'],
                'priority' => $fields['priority'],
                'status' => 'pending',
                'createuser' => auth()->user()->email,
                'createdate' => now(),
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Task creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Task creation failed'
            ]);
        }
    }

    public function deleteTask(Request $request)
    {
        try {
            $task_id = $request->task_id;

            $deleted = TblTask::where('task_id', $task_id)
                ->where('userid', auth()->user()->userid)
                ->update(['deleted' => '1']);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deletion successfull!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Deletion failed. Task not found!'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Deletion failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Deletion failed'
            ]);
        }
    }

    public function updateTask(Request $request)
    {
        try {
            $task = TblTask::findOrFail($request->task_id);

            $fields = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'priority' => 'required|string',
                'status' => 'required|string'
            ]);

            $task->update($fields);

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Task update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Task update failed'
            ]);
        }
    }
}
