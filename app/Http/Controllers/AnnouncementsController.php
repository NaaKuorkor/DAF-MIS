<?php

namespace App\Http\Controllers;

use App\Models\TblAnnouncements;
use App\Models\TblAnnouncementRead;
use App\Models\TblUser;
use App\Models\TblStaff;
use App\Models\TblStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
                'audience' => 'required|string|in:all_staff,all_students,everyone',
                'priority' => 'required|in:info,alert,urgent',
                'scheduled_at' => 'nullable|date|after:now',
            ]);

            $announcement_id = 'ANN-' . strtoupper(Str::random(8));
            
            // Convert single audience value to array format for storage
            $audienceArray = [$fields['audience']];

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
                'audience' => json_encode($audienceArray),
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
                'audience' => 'required|string|in:all_staff,all_students,everyone',
                'priority' => 'required|in:info,alert,urgent',
                'scheduled_at' => 'nullable|date|after:now',
            ]);
            
            // Convert single audience value to array format for storage
            $audienceArray = [$fields['audience']];

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
                'audience' => json_encode($audienceArray),
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
                'audience' => 'nullable|string|in:all_staff,all_students,everyone',
                'priority' => 'nullable|in:info,alert,urgent',
            ]);

            $announcement_id = 'ANN-' . strtoupper(Str::random(8));

            // Convert single audience value to array format for storage
            $audienceValue = $fields['audience'] ?? 'everyone';
            $audienceArray = [$audienceValue];
            
            TblAnnouncements::create([
                'announcement_id' => $announcement_id,
                'title' => $fields['title'] ?? 'Untitled Draft',
                'content' => $fields['content'] ?? '',
                'status' => 'draft',
                'priority' => $fields['priority'] ?? 'info',
                'audience' => json_encode($audienceArray),
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

    /**
     * Get announcements for the current user as a recipient (not creator)
     */
    public function getRecipientsAnnouncements(Request $request)
    {
        try {
            $user = auth()->user();
            $userType = $user->user_type; // 'STA', 'STU', or 'ADM'
            $userid = $user->userid;

            // Determine user role for filtering
            $isStaff = in_array($userType, ['STA', 'ADM']);
            $isStudent = $userType === 'STU';

            // Get user's department and position if staff
            $userDepartment = null;
            $userPosition = null;
            if ($isStaff && $user->staff) {
                $userDepartment = $user->staff->department;
                $userPosition = $user->staff->position;
            }

            // Get all active announcements first (more reliable than complex JSON queries)
            $allAnnouncements = TblAnnouncements::where('deleted', '0')
                ->where('status', 'active')
                ->where('created_by', '!=', $userid)
                ->where(function($q) {
                    // Check expiration
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->orderBy('published_at', 'desc')
                ->orderBy('createdate', 'desc')
                ->get();

            // Filter announcements by audience in PHP (more reliable)
            // Only allow: all_staff, all_students, or everyone
            $filteredAnnouncements = $allAnnouncements->filter(function($announcement) use ($isStaff, $isStudent) {
                // Decode audience JSON
                $audience = is_array($announcement->audience) 
                    ? $announcement->audience 
                    : json_decode($announcement->audience, true);

                if (!$audience || !is_array($audience)) {
                    return false;
                }

                // Everyone gets all announcements
                if (in_array('everyone', $audience)) {
                    return true;
                }

                // Staff only see announcements for staff or everyone
                if ($isStaff) {
                    return in_array('all_staff', $audience);
                }

                // Students only see announcements for students or everyone
                if ($isStudent) {
                    return in_array('all_students', $audience);
                }

                return false;
            });

            // Get read announcement IDs for filtering
            $readAnnouncementIds = TblAnnouncementRead::where('userid', $userid)
                ->pluck('announcement_id')
                ->toArray();

            // Apply read/unread filter if requested
            if ($request->filled('filter')) {
                if ($request->filter === 'unread') {
                    $filteredAnnouncements = $filteredAnnouncements->filter(function($announcement) use ($readAnnouncementIds) {
                        return !in_array($announcement->announcement_id, $readAnnouncementIds);
                    });
                } elseif ($request->filter === 'read') {
                    $filteredAnnouncements = $filteredAnnouncements->filter(function($announcement) use ($readAnnouncementIds) {
                        return in_array($announcement->announcement_id, $readAnnouncementIds);
                    });
                }
            }

            // Manual pagination for filtered results
            $perPage = $request->get('per_page', 10);
            $currentPage = $request->get('page', 1);
            $total = $filteredAnnouncements->count();
            $lastPage = ceil($total / $perPage);
            $offset = ($currentPage - 1) * $perPage;
            
            $announcements = $filteredAnnouncements->slice($offset, $perPage)->values();

            // Add read status for each announcement
            $announcements = $announcements->map(function($announcement) use ($readAnnouncementIds) {
                $announcement->is_read = in_array($announcement->announcement_id, $readAnnouncementIds);
                return $announcement;
            });

            return response()->json([
                'success' => true,
                'data' => $announcements->values()->toArray(),
                'pagination' => [
                    'current_page' => (int)$currentPage,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $total,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load recipient announcements', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcements'
            ], 500);
        }
    }

    /**
     * Get unread announcement count for current user
     */
    public function getUnreadCount()
    {
        try {
            $user = auth()->user();
            $userType = $user->user_type;
            $userid = $user->userid;

            $isStaff = in_array($userType, ['STA', 'ADM']);
            $isStudent = $userType === 'STU';

            // Get user's position if staff
            $userPosition = null;
            if ($isStaff && $user->staff) {
                $userPosition = $user->staff->position;
            }

            // Get all announcement IDs this user should receive (using PHP filtering for reliability)
            $allAnnouncements = TblAnnouncements::where('deleted', '0')
                ->where('status', 'active')
                ->where('created_by', '!=', $userid)
                ->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->get();

            // Filter by audience in PHP
            $filteredAnnouncements = $allAnnouncements->filter(function($announcement) use ($isStaff, $isStudent, $userPosition) {
                $audience = is_array($announcement->audience) 
                    ? $announcement->audience 
                    : json_decode($announcement->audience, true);

                if (!$audience || !is_array($audience)) {
                    return false;
                }

                // Everyone gets all announcements
                if (in_array('everyone', $audience)) {
                    return true;
                }

                // Staff only see announcements for staff or everyone
                if ($isStaff) {
                    return in_array('all_staff', $audience);
                }

                // Students only see announcements for students or everyone
                if ($isStudent) {
                    return in_array('all_students', $audience);
                }

                return false;
            });

            $announcementIds = $filteredAnnouncements->pluck('announcement_id')->toArray();

            // Get read announcement IDs
            $readAnnouncementIds = TblAnnouncementRead::where('userid', $userid)
                ->whereIn('announcement_id', $announcementIds)
                ->pluck('announcement_id')
                ->toArray();

            // Calculate unread count
            $unreadCount = count($announcementIds) - count($readAnnouncementIds);

            return response()->json([
                'success' => true,
                'unread_count' => max(0, $unreadCount)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get unread count', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'unread_count' => 0
            ]);
        }
    }

    /**
     * View a single announcement as a recipient and mark as read
     */
    public function viewRecipientAnnouncement($announcement_id)
    {
        try {
            $user = auth()->user();
            $userid = $user->userid;

            // Get announcement
            $announcement = TblAnnouncements::where('announcement_id', $announcement_id)
                ->where('deleted', '0')
                ->where('status', 'active')
                ->firstOrFail();

            // Verify user should have access to this announcement
            $userType = $user->user_type;
            $isStaff = in_array($userType, ['STA', 'ADM']);
            $isStudent = $userType === 'STU';

            $userPosition = null;
            if ($isStaff && $user->staff) {
                $userPosition = $user->staff->position;
            }

            $audience = is_array($announcement->audience) ? $announcement->audience : json_decode($announcement->audience, true);
            $hasAccess = false;

            if (!$audience || !is_array($audience)) {
                $hasAccess = false;
            } elseif (in_array('everyone', $audience)) {
                $hasAccess = true;
            } elseif ($isStaff && in_array('all_staff', $audience)) {
                $hasAccess = true;
            } elseif ($isStudent && in_array('all_students', $audience)) {
                $hasAccess = true;
            }

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this announcement'
                ], 403);
            }

            // Check expiration
            if ($announcement->expires_at && $announcement->expires_at < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This announcement has expired'
                ], 404);
            }

            // Mark as read if not already read
            if (!$announcement->isReadBy($userid)) {
                $announcement->markAsRead($userid);
            }

            // Increment views count
            $announcement->increment('views_count');

            return response()->json([
                'success' => true,
                'data' => [
                    'announcement' => $announcement->fresh(),
                    'is_read' => true
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to view recipient announcement', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load announcement'
            ], 500);
        }
    }

    /**
     * Mark announcement as read
     */
    public function markAsRead($announcement_id)
    {
        try {
            $user = auth()->user();
            $announcement = TblAnnouncements::where('announcement_id', $announcement_id)
                ->where('deleted', '0')
                ->where('status', 'active')
                ->firstOrFail();

            if (!$announcement->isReadBy($user->userid)) {
                $announcement->markAsRead($user->userid);
            }

            return response()->json([
                'success' => true,
                'message' => 'Announcement marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark announcement as read', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark announcement as read'
            ], 500);
        }
    }

    /**
     * Calculate total recipients for an announcement based on audience
     */
    private function calculateTotalRecipients($audience)
    {
        try {
            $audienceArray = is_array($audience) ? $audience : json_decode($audience, true);
            
            if (empty($audienceArray)) {
                return 0;
            }

            $totalCount = 0;
            $countedUserIds = [];

            foreach ($audienceArray as $audienceType) {
                switch ($audienceType) {
                    case 'everyone':
                        // Count all active users
                        $users = TblUser::where('deleted', '0')
                            ->where('status', '1')
                            ->pluck('userid')
                            ->toArray();
                        
                        foreach ($users as $userId) {
                            if (!in_array($userId, $countedUserIds)) {
                                $countedUserIds[] = $userId;
                                $totalCount++;
                            }
                        }
                        break;

                    case 'all_staff':
                        // Count all staff users
                        $staffUserIds = TblStaff::join('tbluser', 'tblstaff.userid', '=', 'tbluser.userid')
                            ->where('tbluser.deleted', '0')
                            ->where('tbluser.status', '1')
                            ->whereIn('tbluser.user_type', ['STA', 'ADM'])
                            ->pluck('tbluser.userid')
                            ->toArray();
                        
                        foreach ($staffUserIds as $userId) {
                            if (!in_array($userId, $countedUserIds)) {
                                $countedUserIds[] = $userId;
                                $totalCount++;
                            }
                        }
                        break;

                    case 'all_students':
                        // Count all student users
                        $studentUserIds = TblStudent::join('tbluser', 'tblstudent.userid', '=', 'tbluser.userid')
                            ->where('tbluser.deleted', '0')
                            ->where('tbluser.status', '1')
                            ->where('tbluser.user_type', 'STU')
                            ->pluck('tbluser.userid')
                            ->toArray();
                        
                        foreach ($studentUserIds as $userId) {
                            if (!in_array($userId, $countedUserIds)) {
                                $countedUserIds[] = $userId;
                                $totalCount++;
                            }
                        }
                        break;

                    case 'dept_heads':
                        // Count staff with head/director positions
                        $deptHeadUserIds = TblStaff::join('tbluser', 'tblstaff.userid', '=', 'tbluser.userid')
                            ->where('tbluser.deleted', '0')
                            ->where('tbluser.status', '1')
                            ->whereIn('tbluser.user_type', ['STA', 'ADM'])
                            ->where(function($q) {
                                $q->whereRaw("LOWER(tblstaff.position) LIKE ?", ['%head%'])
                                  ->orWhereRaw("LOWER(tblstaff.position) LIKE ?", ['%director%']);
                            })
                            ->pluck('tbluser.userid')
                            ->toArray();
                        
                        foreach ($deptHeadUserIds as $userId) {
                            if (!in_array($userId, $countedUserIds)) {
                                $countedUserIds[] = $userId;
                                $totalCount++;
                            }
                        }
                        break;

                    case 'academic_staff':
                        // Count staff with academic positions
                        $academicUserIds = TblStaff::join('tbluser', 'tblstaff.userid', '=', 'tbluser.userid')
                            ->where('tbluser.deleted', '0')
                            ->where('tbluser.status', '1')
                            ->whereIn('tbluser.user_type', ['STA', 'ADM'])
                            ->whereRaw("LOWER(tblstaff.position) LIKE ?", ['%academic%'])
                            ->pluck('tbluser.userid')
                            ->toArray();
                        
                        foreach ($academicUserIds as $userId) {
                            if (!in_array($userId, $countedUserIds)) {
                                $countedUserIds[] = $userId;
                                $totalCount++;
                            }
                        }
                        break;
                }
            }

            return $totalCount;
        } catch (\Exception $e) {
            Log::error('Failed to calculate total recipients', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a safe default instead of 0
            return 0;
        }
    }
}