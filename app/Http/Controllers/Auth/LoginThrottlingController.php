<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginThrottlingController extends Controller
{
    /**
     * The maximum number of attempts to allow.
     */
    protected int $maxAttempts = 5;

    /**
     * The number of minutes to throttle for.
     */
    protected int $decayMinutes = 1;

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkLoginThrottling(Request $request): void
    {
        if (! $this->hasTooManyLoginAttempts($request)) {
            return;
        }

        $this->fireLockoutEvent($request);

        // Log the lockout
        ActivityLog::log('auth.lockout', 'Account locked due to too many failed login attempts', [
            'email' => $request->email,
            'ip_address' => $request->ip(),
        ]);

        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            'email' => [Lang::get('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ])->status(429);
    }

    /**
     * Increment the login attempts for the user.
     */
    public function incrementLoginAttempts(Request $request): void
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes * 60
        );

        // Log the failed attempt
        ActivityLog::log('auth.failed_attempt', 'Failed login attempt', [
            'email' => $request->email,
            'ip_address' => $request->ip(),
        ]);
    }

    /**
     * Clear the login attempts for the user.
     */
    public function clearLoginAttempts(Request $request): void
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Determine if the user has too many failed login attempts.
     */
    protected function hasTooManyLoginAttempts(Request $request): bool
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts
        );
    }

    /**
     * Fire an event when a lockout occurs.
     */
    protected function fireLockoutEvent(Request $request): void
    {
        event(new Lockout($request));
    }

    /**
     * Get the rate limiter instance.
     */
    protected function limiter(): RateLimiter
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(
            Str::lower($request->input('email')).'|'.$request->ip()
        );
    }
}
