<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        if ($this->shouldLogRequest($request)) {
            $this->logRequest($request, $response);
        }
    }

    /**
     * Determine if the request should be logged.
     */
    protected function shouldLogRequest(Request $request): bool
    {
        // Don't log asset requests
        if ($this->isAssetRequest($request)) {
            return false;
        }

        // Don't log activity log requests to prevent infinite loops
        if ($this->isActivityLogRequest($request)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the request is for an asset.
     */
    protected function isAssetRequest(Request $request): bool
    {
        $path = $request->path();
        
        return preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$/i', $path);
    }

    /**
     * Check if the request is for the activity log.
     */
    protected function isActivityLogRequest(Request $request): bool
    {
        return $request->is('admin/activity-logs*') || $request->is('profile/activity');
    }

    /**
     * Log the request.
     */
    protected function logRequest(Request $request, Response $response): void
    {
        $action = $this->determineAction($request);
        
        if (!$action) {
            return;
        }

        $properties = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
        ];

        // Add request data for non-GET requests
        if ($request->method() !== 'GET') {
            // Filter out sensitive data
            $input = $request->except(['password', 'password_confirmation', 'current_password', 'token', '_token']);
            
            if (!empty($input)) {
                $properties['request_data'] = $input;
            }
        }

        ActivityLog::log($action, $this->getDescription($request), $properties);
    }

    /**
     * Determine the action based on the request.
     */
    protected function determineAction(Request $request): ?string
    {
        $method = $request->method();
        $path = $request->path();

        // Authentication actions
        if ($path === 'login' && $method === 'POST') {
            return 'user.login';
        }

        if ($path === 'logout' && $method === 'POST') {
            return 'user.logout';
        }

        if ($path === 'register' && $method === 'POST') {
            return 'user.register';
        }

        // Profile actions
        if (preg_match('/^profile/', $path)) {
            if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
                return 'profile.update';
            }
            
            if ($method === 'DELETE') {
                return 'profile.delete';
            }
        }

        // Two-factor authentication actions
        if (preg_match('/^profile\/two-factor/', $path)) {
            if ($method === 'POST') {
                return 'two-factor.enable';
            }
            
            if ($method === 'DELETE') {
                return 'two-factor.disable';
            }
        }

        // Admin actions
        if (preg_match('/^admin\/users/', $path)) {
            if ($method === 'PUT' || $method === 'PATCH') {
                return 'admin.user.update';
            }
        }

        // Course actions
        if (preg_match('/^admin\/courses/', $path)) {
            if ($method === 'POST') {
                return 'course.create';
            }
            
            if ($method === 'PUT' || $method === 'PATCH') {
                return 'course.update';
            }
            
            if ($method === 'DELETE') {
                return 'course.delete';
            }
        }

        // Quiz actions
        if (preg_match('/^admin\/quizzes/', $path)) {
            if ($method === 'POST') {
                return 'quiz.create';
            }
            
            if ($method === 'PUT' || $method === 'PATCH') {
                return 'quiz.update';
            }
            
            if ($method === 'DELETE') {
                return 'quiz.delete';
            }
        }

        // For other requests, return null to skip logging
        return null;
    }

    /**
     * Get a description for the activity.
     */
    protected function getDescription(Request $request): ?string
    {
        $path = $request->path();
        $method = $request->method();

        if ($path === 'login' && $method === 'POST') {
            return 'User attempted to log in';
        }

        if ($path === 'logout' && $method === 'POST') {
            return 'User logged out';
        }

        if ($path === 'register' && $method === 'POST') {
            return 'New user registration';
        }

        return null;
    }
}
