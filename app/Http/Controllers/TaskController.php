<?php

namespace App\Http\Controllers;

use App\Models\TblTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelWriter;

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

            $task_id = 'TSK-' . strtoupper(Str::random(6));

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

    public function deleteTask($task_id)
    {
        try{

            $deleted = TblTask::where('task_id', $task_id)
                ->where('userid', auth()->user()->userid)
                ->where('deleted', '0')
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

    public function showTask($task_id)
    {
        try {
            $task = TblTask::where('task_id', $task_id)
                ->where('userid', auth()->user()->userid)
                ->where('deleted', '0')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ]);
        }
    }

    public function indexTasks(Request $request)
    {
        try {
            $query = TblTask::where('userid', auth()->user()->userid)
                ->where('deleted', '0');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
            }

            $tasks = $query->orderBy('due_date')->get();

            return response()->json([
                'success' => true,
                'data' => $tasks
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Tasks failed to load'
            ]);
        }
    }

    public function updateTask(Request $request, $task_id)
    {
        try {
            $task = TblTask::where('userid', auth()->user()->userid)
                ->where('task_id', $task_id)
                ->where('deleted', '0')
                ->firstOrFail();

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

    public function markCompleted(Request $request)
    {
        try {

            $task = TblTask::where('userid', auth()->user()->userid)
                ->where('task_id', $request->task_id)
                ->where('deleted', '0')
                ->firstOrFail();

            if ($task->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Task already completed'
                ]);
            }

            $task->update([
                'completed_at' => now(),
                'status' => 'completed',
                'modifyuser' => auth()->user()->email,
                'modifydate' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Marked as complete'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to complete task', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete task'
            ]);
        }
    }

    public function exportTasks()
    {
        $task = TblTask::where('userid', auth()->user()->userid)
            ->where('deleted', '0')
            ->orderBy('createdate')
            ->get();


        $name = auth()->user()->fname;
        $writer = SimpleExcelWriter::streamDownload('Tasks_' . $name . '.xlsx');

        //Add data rows
        foreach ($task as $t) {
            $writer->addRow([
                'Task Id' => $t->task_id,
                'title' => $t->title,
                'Description' => $t->description,
                'Due Date' => $t->due_date,
                'Priority' => $t->priority,
                'Status' => $t->status,
                'Time Completed' => $t->completed_at
            ]);
        }

        return $writer->toBrowser();
    }
}
