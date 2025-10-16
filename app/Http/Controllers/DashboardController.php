<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        \Log::info('DashboardController::index called', [
            'web_auth_check' => Auth::check(),
            'supervisor_auth_check' => Auth::guard('supervisor')->check(),
            'user_id' => Auth::user() ? Auth::user()->id : null
        ]);
        
        // Check if supervisor is logged in
        if (Auth::guard('supervisor')->check()) {
            \Log::info('Redirecting supervisor to dashboard');
            return redirect()->route('supervisor.dashboard');
        }

        if (!Auth::check()) {
            \Log::info('No user authenticated, redirecting to login');
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        \Log::info('User authenticated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_approved' => $user->is_approved,
            'role_id' => $user->role_id
        ]);
        
        if (!$user->is_approved) {
            \Log::warning('User not approved, logging out');
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is pending approval.');
        }

        // Redirect based on user role
        if ($user->isSuperAdmin()) {
            \Log::info('Redirecting SuperAdmin to dashboard');
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->isAdmin()) {
            \Log::info('Redirecting Admin to dashboard');
            return redirect()->route('admin.dashboard');
        } else {
            \Log::info('Redirecting User to dashboard');
            return redirect()->route('user.dashboard');
        }
    }
}
