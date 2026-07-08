<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isAdmin()) {
            if ($user->isCoach()) {
                return redirect('/coach/dashboard');
            }
            if ($user->isStudent()) {
                return redirect('/student/dashboard');
            }
            return redirect('/');
        }

        // Admin abonelik kontrolü (Dershane abonelik kontrolü) - SuperAdmin için atla
        if (!$user->isSuperAdmin()) {
            $subscription = $user->subscription;
            if (!$subscription || !$subscription->is_active || ($subscription->end_date && $subscription->end_date->isPast())) {
                return redirect()->route('subscription.expired');
            }
        }

        $response = $next($request);
        
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        
        return $response;
    }
}
