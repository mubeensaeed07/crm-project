<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments for the current admin.
     */
    public function index()
    {
        $admin = Auth::user();
        $departments = Department::where('admin_id', $admin->id)
            ->orderBy('name')
            ->get();

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:departments,code',
            'is_active' => 'boolean'
        ]);

        $admin = Auth::user();
        
        Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'code' => strtoupper($request->code),
            'is_active' => $request->has('is_active'),
            'admin_id' => $admin->id
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully!');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit($id)
    {
        $admin = Auth::user();
        $department = Department::where('admin_id', $admin->id)->findOrFail($id);
        
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id)
    {
        $admin = Auth::user();
        $department = Department::where('admin_id', $admin->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:departments,code,' . $id,
            'is_active' => 'boolean'
        ]);

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'code' => strtoupper($request->code),
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully!');
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        $admin = Auth::user();
        $department = Department::where('admin_id', $admin->id)->findOrFail($id);

        // Check if department has users
        if ($department->userInfos()->count() > 0) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Cannot delete department. It has assigned users.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully!');
    }

    /**
     * Get departments for the current admin (for AJAX requests).
     */
    public function getDepartments()
    {
        $admin = Auth::user();
        $departments = Department::where('admin_id', $admin->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($departments);
    }
}