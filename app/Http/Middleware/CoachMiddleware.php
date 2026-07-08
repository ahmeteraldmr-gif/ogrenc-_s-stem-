<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoachMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Giriş yapılmamışsa login sayfasına yönlendir
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Koç değilse kendi paneline veya anasayfaya yönlendir
        if (!$user->isCoach()) {
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            if ($user->isStudent()) {
                return redirect('/student/dashboard');
            }
            return redirect('/');
        }

        // Genel Dershane (Admin) Abonelik Kontrolü
        $admin = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'admin');
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

        $subscription = $user->subscription;

        // Koçun kendi erişim yetkisi/abonelik kontrolü
        if (!$subscription || !$subscription->is_active || ($subscription->end_date && $subscription->end_date->isPast())) {
            // Livewire AJAX isteği ise JSON redirect dön
            if ($request->hasHeader('X-Livewire')) {
                return response()->json([
                    'redirect' => url('/subscription-expired'),
                ], 200);
            }
            return redirect()->route('subscription.expired');
        }

        $response = $next($request);
        
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        
        return $response;
    }
}
