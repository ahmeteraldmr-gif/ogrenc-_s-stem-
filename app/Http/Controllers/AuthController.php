<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login sayfasını göster
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login işlemini gerçekleştir
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Kullanıcının rolüne göre yönlendir
            if ($user->isAdmin()) {
                // Admin abonelik kontrolü
                $subscription = $user->subscription;
                if (!$subscription || !$subscription->is_active || ($subscription->end_date && $subscription->end_date->isPast())) {
                    return redirect()->route('subscription.expired');
                }
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isCoach()) {
                // Genel dershane abonelik kontrolü
                $admin = \App\Models\User::whereHas('role', function($q) {
                    $q->whereIn('name', ['admin', 'superadmin']);
                })->first();
                if ($admin) {
                    $adminSub = $admin->subscription;
                    if (!$adminSub || !$adminSub->is_active || ($adminSub->end_date && $adminSub->end_date->isPast())) {
                        return redirect()->route('subscription.expired');
                    }
                }

                // Koçun bireysel abonelik kontrolü - süresi dolmuşsa özel sayfaya yönlendir
                $subscription = $user->subscription;
                if (!$subscription || !$subscription->is_active || ($subscription->end_date && $subscription->end_date->isPast())) {
                    return redirect()->route('subscription.expired');
                }
                return redirect()->intended('/coach/dashboard');
            } elseif ($user->isStudent()) {
                // Genel dershane abonelik kontrolü
                $admin = \App\Models\User::whereHas('role', function($q) {
                    $q->whereIn('name', ['admin', 'superadmin']);
                })->first();
                if ($admin) {
                    $adminSub = $admin->subscription;
                    if (!$adminSub || !$adminSub->is_active || ($adminSub->end_date && $adminSub->end_date->isPast())) {
                        return redirect()->route('subscription.expired');
                    }
                }
                return redirect()->intended('/student/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Giriş bilgileri hatalı.',
        ])->onlyInput('email');
    }

    /**
     * Logout işlemini gerçekleştir
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
