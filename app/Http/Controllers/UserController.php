<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Validator;

class UserController extends Controller
{
    public function showRegister(){
        return view('public.Auth.signup');
    }

    public function showLogIn(){
        return view('public.Auth.login');
    }

    public function showAbout(){
        return view('public.about');
    }

    public function showCourses(){
        return view('public.courses');
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Validator::validateEmail($request->email)) {
            return redirect()->back()->with('error', 'Email already exists');
        }
        if (!Validator::validateUsername($request->name)) {
            return redirect()->back()->with('error', 'Username already exists');
        }
        if (!Validator::validatepassword($request->password)) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters');
        }

        $user = new User();
        $user->username = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $firstUser = User::first();
        if(!$firstUser){
            $user->role = 'admin';
        } else {
            $user->role = 'user';
        }

        $user->save();
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check for too many login attempts
        $throttlingController = new \App\Http\Controllers\Auth\LoginThrottlingController();
        $throttlingController->checkLoginThrottling($request);

        $user = User::where('email', $request->email)->first();
        if(!$user){
            // Increment login attempts
            $throttlingController->incrementLoginAttempts($request);

            // Log failed login
            \App\Models\ActivityLog::log('auth.failed', 'Failed login attempt - Invalid email', [
                'email' => $request->email,
            ]);

            return redirect()->back()->with('error', 'Invalid email');
        }

        if(!Hash::check($request->password, $user->password)){
            // Increment login attempts
            $throttlingController->incrementLoginAttempts($request);

            // Log failed login
            \App\Models\ActivityLog::log('auth.failed', 'Failed login attempt - Invalid password', [
                'email' => $request->email,
            ]);

            return redirect()->back()->with('error', 'Invalid password');
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Clear login attempts
            $throttlingController->clearLoginAttempts($request);

            $user = Auth::user();

            if ($user->is_banned) {
                // Log banned user attempt
                \App\Models\ActivityLog::log('auth.banned', 'Banned user attempted to login', [
                    'user_id' => $user->id,
                ]);

                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is banned.');
            }

            // Log successful login
            \App\Models\ActivityLog::log('auth.login', 'Successful login');

            // Regenerate session
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'agent') {
                return redirect()->route('agent.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }
    }

    public function LogOut(Request $request){
        try {
            // Log the activity before logging out
            if (Auth::check()) {
                try {
                    \App\Models\ActivityLog::log('auth.logout', 'User logged out');
                } catch (\Exception $e) {
                    // Silently fail if activity logging fails
                }
            }

            // Logout the user
            Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the CSRF token
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'You have been successfully logged out.');
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Logout error: ' . $e->getMessage());

            // Force logout even if there was an error
            Auth::logout();

            return redirect()->route('login')->with('success', 'You have been logged out.');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        // Log the activity
        \App\Models\ActivityLog::log('profile.update', 'Updated profile information');

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Log the activity
        \App\Models\ActivityLog::log('password.update', 'Updated password');

        return redirect()->route('profile.edit')->with('status', 'Password updated successfully.');
    }
}
