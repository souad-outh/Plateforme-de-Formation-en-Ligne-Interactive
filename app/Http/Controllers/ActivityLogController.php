<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('viewActivityLogs', Auth::user());
        
        $query = ActivityLog::with('user')->latest();
        
        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activityLogs = $query->paginate(20);
        
        return view('admin.activity-logs.index', [
            'activityLogs' => $activityLogs,
            'actions' => ActivityLog::distinct('action')->pluck('action'),
        ]);
    }
    
    /**
     * Display the specified activity log.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $this->authorize('viewActivityLogs', Auth::user());
        
        $activityLog = ActivityLog::with('user')->findOrFail($id);
        
        return view('admin.activity-logs.show', [
            'activityLog' => $activityLog,
        ]);
    }
    
    /**
     * Display the current user's activity logs.
     *
     * @return \Illuminate\View\View
     */
    public function userActivity()
    {
        $user = Auth::user();
        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
        return view('profile.activity', [
            'activityLogs' => $activityLogs,
        ]);
    }
    
    /**
     * Clear all activity logs.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        $this->authorize('clearActivityLogs', Auth::user());
        
        ActivityLog::truncate();
        
        ActivityLog::log('activity_logs.cleared', 'Cleared all activity logs');
        
        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'All activity logs have been cleared.');
    }
}
