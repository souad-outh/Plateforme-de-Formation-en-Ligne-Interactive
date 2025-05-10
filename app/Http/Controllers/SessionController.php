<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SessionController extends Controller
{
    /**
     * Display a listing of the user's sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sessions = $this->getSessions($request);
        
        return view('profile.sessions', [
            'sessions' => $sessions,
        ]);
    }
    
    /**
     * Log the user out of all other browser sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyOtherSessions(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Check if the current password matches
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The provided password does not match your current password.']);
        }
        
        // Update the user's session ID to a new one
        $this->updateSessionId($request);
        
        // Delete all other sessions for this user
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();
        
        // Log the activity
        ActivityLog::log('sessions.logout_others', 'Logged out of all other browser sessions');
        
        return redirect()->route('profile.sessions')->with('status', 'All other browser sessions have been logged out.');
    }
    
    /**
     * Get the current sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    protected function getSessions(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }
        
        $user = Auth::user();
        
        return collect(
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => $this->formatLastActive($session),
            ];
        });
    }
    
    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \stdClass
     */
    protected function createAgent($session)
    {
        $agent = $session->user_agent;
        
        // Extract browser and platform information
        $browser = 'Unknown';
        $platform = 'Unknown';
        
        if (preg_match('/MSIE|Trident/i', $agent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $agent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $agent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $agent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera|OPR/i', $agent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Edge/i', $agent)) {
            $browser = 'Edge';
        }
        
        if (preg_match('/Windows/i', $agent)) {
            $platform = 'Windows';
        } elseif (preg_match('/Macintosh|Mac OS X/i', $agent)) {
            $platform = 'Mac';
        } elseif (preg_match('/Linux/i', $agent)) {
            $platform = 'Linux';
        } elseif (preg_match('/Android/i', $agent)) {
            $platform = 'Android';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $agent)) {
            $platform = 'iOS';
        }
        
        return (object) [
            'browser' => $browser,
            'platform' => $platform,
            'is_desktop' => !preg_match('/mobile|android|iphone|ipad|ipod/i', $agent),
        ];
    }
    
    /**
     * Format the last active time for the session.
     *
     * @param  mixed  $session
     * @return string
     */
    protected function formatLastActive($session)
    {
        return now()->subSeconds(now()->timestamp - $session->last_activity)->diffForHumans();
    }
    
    /**
     * Update the session ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function updateSessionId(Request $request)
    {
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        DB::table('sessions')
            ->where('id', $request->session()->getId())
            ->update([
                'user_id' => $user->id,
            ]);
    }
}
