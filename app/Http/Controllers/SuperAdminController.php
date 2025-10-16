<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\UserModule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminApprovedMail;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $pendingAdmins = User::where('role_id', 2)->where('is_approved', false)->get();
        $approvedAdmins = User::where('role_id', 2)->where('is_approved', true)->get();
        $regularUsers = User::where('role_id', 3)->get();
        $modules = Module::all();
        
        return view('superadmin.dashboard', compact(
            'totalUsers', 
            'pendingAdmins', 
            'approvedAdmins', 
            'regularUsers', 
            'modules'
        ));
    }

    public function approveAdmin($id)
    {
        $user = User::findOrFail($id);
        $superAdmin = auth()->user();
        
        // Update user approval status and set superadmin_id
        $user->update([
            'is_approved' => true,
            'superadmin_id' => $superAdmin->id
        ]);
        
        // Send approval email
        try {
            Mail::to($user->email)->send(new AdminApprovedMail($user, $superAdmin->full_name));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin approval email: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('success', 'Admin approved successfully! Approval email sent.');
    }

    public function rejectAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->back()->with('success', 'Admin application rejected.');
    }

    public function createModule(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Module::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'bx bx-grid-alt'
        ]);

        return redirect()->back()->with('success', 'Module created successfully!');
    }

    public function updateModule(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'bx bx-grid-alt'
        ]);

        return redirect()->back()->with('success', 'Module updated successfully!');
    }

    public function deleteModule($id)
    {
        try {
            $module = Module::findOrFail($id);
            
            // Get count of users assigned to this module before deletion
            $assignedUsersCount = UserModule::where('module_id', $id)->count();
            
            // First, remove all user assignments for this module
            UserModule::where('module_id', $id)->delete();
            
            // Then delete the module itself
            $module->delete();

            $message = 'Module "' . $module->name . '" deleted successfully!';
            if ($assignedUsersCount > 0) {
                $message .= ' Removed from ' . $assignedUsersCount . ' user(s).';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete module: ' . $e->getMessage());
        }
    }

    public function getPendingAdmins()
    {
        $pendingAdmins = User::where('role_id', 2)->where('is_approved', false)->get();
        return response()->json($pendingAdmins);
    }
}
