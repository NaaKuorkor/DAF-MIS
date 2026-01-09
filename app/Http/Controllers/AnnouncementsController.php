<?php

namespace App\Http\Controllers;

use App\Models\TblAnnouncements;
use App\Models\TblAnnouncementRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnnouncementsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = TblAnnouncements::where('deleted', '0')
                ->where('created_by', auth()->user()->userid);

            // Filter by status
            if ($request->filled('filter')) {
                $filter = $request->filter;
                if ($filter === 'drafts') {
                    $query->where('status', 'draft');
                } elseif ($filter === 'scheduled') {
                    $query->where('status', 'scheduled');
                } elseif ($filter === 'active') {
                    $query->where('status', 'active');
                }
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('content', 'like', '%' . $search . '%');
                });
            }

            $announcements = $query->orderBy('createdate', 'desc')
                ->paginate(15);

            // Get stats
            $activeCount = TblAnnouncements::where('deleted', '0')
                ->where('created_by', auth()->user()->userid)
                ->where('status', 'active')
                ->count();

            $scheduledCount = TblAnnouncements::where('deleted', '0')
                ->where('created_by', auth()->user()->userid)
                ->where('status', 'scheduled')
                ->count();

            return response()->json([
                'success' => true,
                'data' => $announcements->items(),
                'pagination' => [
                    'current_page' => $announcements->currentPage(),
                    'last_page' => $announcements->lastPage(),
                    'per_page' => $announcements->perPage(),
                    'total' => $announcements->total(),
                ],
                'stats' => [
                    'active' => $activeCount,
                    'scheduled' => $scheduledCount,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load announcements', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcements'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $fields = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'audience' => 'required|array',
                'audience.*' => 'in:all_staff,all_students,dept_heads,academic_staff,everyone',
                'priority' => 'required|in:info,alert,urgent',
                'scheduled_at' => 'nullable|date|after:now',
            ]);

            $announcement_id = 'ANN-' . strtoupper(Str::random(8));

            $status = 'draft';
            $published_at = null;

            // If scheduled, set status to scheduled
            if ($request->filled('scheduled_at') && $request->scheduled_at) {
                $status = 'scheduled';
            } elseif ($request->action === 'broadcast') {
                // If broadcasting now, set to active
                $status = 'active';
                $published_at = now();
            }

            TblAnnouncements::create([
                'announcement_id' => $announcement_id,
                'title' => $fields['title'],
                'content' => $fields['content'],
                'status' => $status,
                'priority' => $fields['priority'],
                'audience' => json_encode($fields['audience']),
                'scheduled_at' => $request->scheduled_at ?? null,
                'published_at' => $published_at,
                'created_by' => auth()->user()->userid,
                'deleted' => '0',
                'createuser' => auth()->user()->email,
                'createdate' => now(),
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $status === 'active' ? 'Announcement broadcasted successfully' : 'Announcement saved as draft',
                'data' => ['announcement_id' => $announcement_id]
            ]);
        } catch (\Exception $e) {
            Log::error('Announcement creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create announcement'
            ], 500);
        }
    }

    public function show($announcement_id)
    {
        try {
            $announcement = TblAnnouncements::where('announcement_id', $announcement_id)
                ->where('deleted', '0')
                ->where('created_by', auth()->user()->userid)
                ->firstOrFail();

            // Calculate read rate
            $totalRecipients = $this->calculateTotalRecipients($announcement->audience);
            $readRate = $totalRecipients > 0 
                ? round(($announcement->read_count / $totalRecipients) * 100, 1) 
                : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'announcement' => $announcement,
                    'read_rate' => $readRate,
                    'total_recipients' => $totalRecipients,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load announcement', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Announcement not found'
            ], 404);
        }
    }

    public function update(Request $request, $announcement_id)
    {
        try {
            $announcement = TblAnnouncements::where('announcement_id', $announcement_id)
                ->where('deleted', '0')
                ->where('created_by', auth()->user()->userid)
                ->firstOrFail();

            $fields = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'audience' => 'required|array',
                'audience.*' => 'in:all_staff,all_students,dept_heads,academic_staff,everyone',
                'priority' => 'required|in:info,alert,urgent',
                'scheduled_at' => 'nullable|date|after:now',
            ]);

            $status = $announcement->status;
            $published_at = $announcement->published_at;

            // Handle status changes
            if ($request->action === 'broadcast' && $status !== 'active') {
                $status = 'active';
                $published_at = now();
            } elseif ($request->filled('scheduled_at') && $request->scheduled_at) {
                $status = 'scheduled';
            } elseif ($request->action === 'save_draft') {
                $status = 'draft';
            }

            $announcement->update([
                'title' => $fields['title'],
                'content' => $fields['content'],
                'status' => $status,
                'priority' => $fields['priority'],
                'audience' => json_encode($fields['audience']),
                'scheduled_at' => $request->scheduled_at ?? $announcement->scheduled_at,
                'published_at' => $published_at,
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Announcement update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update announcement'
            ], 500);
        }
    }

    public function destroy($announcement_id)
    {
        try {
            $announcement = TblAnnouncements::where('announcement_id', $announcement_id)
                ->where('deleted', '0')
                ->where('created_by', auth()->user()->userid)
                ->firstOrFail();

            $announcement->update([
                'deleted' => '1',
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Announcement deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Announcement deletion failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete announcement'
            ], 500);
        }
    }

    public function saveDraft(Request $request)
    {
        try {
            $fields = $request->validate([
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'audience' => 'nullable|array',
                'priority' => 'nullable|in:info,alert,urgent',
            ]);

            $announcement_id = 'ANN-' . strtoupper(Str::random(8));

            TblAnnouncements::create([
                'announcement_id' => $announcement_id,
                'title' => $fields['title'] ?? 'Untitled Draft',
                'content' => $fields['content'] ?? '',
                'status' => 'draft',
                'priority' => $fields['priority'] ?? 'info',
                'audience' => json_encode($fields['audience'] ?? ['everyone']),
                'created_by' => auth()->user()->userid,
                'deleted' => '0',
                'createuser' => auth()->user()->email,
                'createdate' => now(),
                'modifyuser' => auth()->user()->email,
                'modifydate' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully',
                'data' => ['announcement_id' => $announcement_id]
            ]);
        } catch (\Exception $e) {
            Log::error('Draft save failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft'
            ], 500);
        }
    }

    private function calculateTotalRecipients($audience)
    {
        // This is a placeholder - you'd need to query actual user counts
        // based on the audience types
        $count = 0;
        $audienceArray = is_array($audience) ? $audience : json_decode($audience, true);
        
        // You would implement actual counting logic here
        // For now, return a placeholder
        return 100; // Placeholder
    }
}