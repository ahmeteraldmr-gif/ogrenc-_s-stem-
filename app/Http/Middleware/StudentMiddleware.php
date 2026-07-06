<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
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

        if (!$user->isStudent()) {
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            if ($user->isCoach()) {
                return redirect('/coach/dashboard');
            }
            return redirect('/');
        }

        // Genel Dershane (Admin) Abonelik Kontrolü
        $admin = \App\Models\User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'superadmin']);
        })->first();

        if ($admin) {
            $adminSub = $admin->subscription;
            if (!$adminSub || !$adminSub->is_active || ($adminSub->end_date && $adminSub->end_date->isPast())) {
                if ($request->hasHeader('X-Livewire')) {
                    return response()->json([
                        'redirect' => url('/subscription-expired'),
                    ], 200);
                }
                return redirect()->route('subscription.expired');
            }
        }

        return $next($request);
    }
}
