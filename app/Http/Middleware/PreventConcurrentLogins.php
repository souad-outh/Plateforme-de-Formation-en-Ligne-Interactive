<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class PreventConcurrentLogins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            try {
                $user = Auth::user();
                $currentSessionId = Session::getId();

                // Check if sessions table exists
                if (Schema::hasTable('sessions')) {
                    // Get all sessions for this user
                    $sessions = DB::table('sessions')
                        ->where('user_id', $user->id)
                        ->where('id', '!=', $currentSessionId)
                        ->get();

                    // If there are other active sessions, log them out
                    if ($sessions->count() > 0) {
                        // Delete all other sessions
                        DB::table('sessions')
                            ->where('user_id', $user->id)
                            ->where('id', '!=', $currentSessionId)
                            ->delete();

                        // Log the activity
                        if (class_exists('\App\Models\ActivityLog')) {
                            \App\Models\ActivityLog::log('session.concurrent_login', 'Logged out from other devices due to concurrent login policy');
                        }
                    }
                }
            } catch (\Exception $e) {
                // Log the error but don't interrupt the user experience
                \Illuminate\Support\Facades\Log::error('Session management error: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
