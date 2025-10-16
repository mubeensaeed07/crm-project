<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Google OAuth for Sign Up
    public function redirectToGoogleSignup()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleSignupCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            \Log::info('Google OAuth Sign Up successful', [
                'email' => $googleUser->email,
                // name field removed - using first_name and last_name separately
                'id' => $googleUser->id
            ]);
            
            $user = User::where('email', $googleUser->email)->first();
            
            if ($user) {
                // User already exists - redirect to sign in
                return redirect()->route('login')->with('error', 'Account already exists. Please sign in instead.');
            } else {
                // Get the SuperAdmin ID (role_id = 1)
                $superAdmin = User::where('role_id', 1)->first();
                $superAdminId = $superAdmin ? $superAdmin->id : null;
                
                // New user - create account and require SuperAdmin approval
                $user = User::create([
                    'first_name' => $googleUser->user['given_name'] ?? '',
                    'last_name' => $googleUser->user['family_name'] ?? '',
                    // name field removed - using first_name and last_name separately
                    'email' => $googleUser->email,
                    'role_id' => 2, // Admin role for new registrations
                    'is_approved' => false,
                    'superadmin_id' => $superAdminId, // Set superadmin_id in users table
                    'password' => Hash::make('google_oauth_user'),
                    'created_by_type' => 'system', // System-created user via Google OAuth
                    'created_by_id' => 1 // Default system ID
                ]);
                
                // Create user info with hierarchy
                $user->userInfo()->create([
                    'admin_id' => null, // New admin, no admin above them
                    'superadmin_id' => $superAdminId
                ]);
                
                // Create user identification record
                DB::table('user_identification')->insert([
                    'user_id' => $user->id,
                    'admin_id' => null,
                    'superadmin_id' => $superAdminId,
                    'user_role' => 'admin',
                    'status' => 'pending',
                    'approved_at' => null,
                    'assigned_at' => now(),
                    'notes' => 'Created via Google OAuth Sign Up',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                \Log::info('New user created via Google OAuth Sign Up', ['user_id' => $user->id, 'superadmin_id' => $superAdminId]);
                
                return redirect()->route('register')->with('success', 'Account created successfully! Please wait for SuperAdmin approval.');
            }
        } catch (\Exception $e) {
            \Log::error('Google OAuth Sign Up failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('register')->with('error', 'Google sign up failed: ' . $e->getMessage());
        }
    }

    // Google OAuth for Sign In
    public function redirectToGoogleSignin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleSigninCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            \Log::info('Google OAuth Sign In successful', [
                'email' => $googleUser->email,
                // name field removed - using first_name and last_name separately
                'id' => $googleUser->id
            ]);
            
            $user = User::where('email', $googleUser->email)->first();
            $supervisor = \App\Models\Supervisor::where('email', $googleUser->email)->first();
            
            \Log::info('User lookup result', [
                'email' => $googleUser->email,
                'user_exists' => $user ? 'Yes' : 'No',
                'supervisor_exists' => $supervisor ? 'Yes' : 'No',
                'user_id' => $user ? $user->id : null,
                'supervisor_id' => $supervisor ? $supervisor->id : null
            ]);
            
            if ($user) {
                \Log::info('Google OAuth User Found', [
                    'user_id' => $user->id,
                    'is_approved' => $user->is_approved,
                    'password_is_google' => $user->password === 'google_oauth_user',
                    'role_id' => $user->role_id
                ]);
                
                // User exists, log them in directly
                \Log::info('User found, checking approval status', [
                    'user_id' => $user->id,
                    'is_approved' => $user->is_approved,
                    'password_type' => $user->password === 'google_oauth_user' ? 'google_oauth' : 'regular'
                ]);
                
                if (!$user->is_approved) {
                    \Log::info('User not approved, redirecting to login');
                    return redirect()->route('login')->with('error', 'Your account is pending approval.');
                }
                
                \Log::info('Logging in user and redirecting');
                Auth::login($user);
                
                // Redirect based on user role instead of using intended()
                if ($user->isSuperAdmin()) {
                    return redirect()->route('superadmin.dashboard');
                } elseif ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('user.dashboard');
                }
            } elseif ($supervisor) {
                \Log::info('Google OAuth Supervisor Found', [
                    'supervisor_id' => $supervisor->id,
                    'status' => $supervisor->status,
                    'email' => $supervisor->email
                ]);
                
                if ($supervisor->status !== 'active') {
                    \Log::info('Supervisor not active, redirecting to login');
                    return redirect()->route('login')->with('error', 'Your supervisor account is not active.');
                }
                
                \Log::info('Logging in supervisor and redirecting');
                Auth::guard('supervisor')->login($supervisor);
                
                return redirect()->route('supervisor.dashboard');
            } else {
                \Log::info('User not found, redirecting to register');
                // User doesn't exist - redirect to sign up
                return redirect()->route('register')->with('info', 'No account found with this email address. Please sign up first.');
            }
        } catch (\Exception $e) {
            \Log::error('Google OAuth Sign In failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')->with('error', 'Google sign in failed: ' . $e->getMessage());
        }
    }

    public function showProfileSetup()
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login');
        }
        
        return view('auth.profile-setup', compact('user'));
    }

    public function completeProfile(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login');
        }

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($request->password)
        ]);

        session()->forget('user');
        
        return redirect()->route('login')->with('success', 'Profile completed! Please wait for admin approval.');
    }
}
