<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Department;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['department', 'manager'])->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $departments = Department::where('status', 'active')->get();
        $managers = User::where('status', 'active')->get();
        $projectCode = 'PRJ-' . date('Y') . '-' . str_pad(Project::count() + 1, 3, '0', STR_PAD_LEFT);
        
        return view('projects.form', [
            'project' => new Project(['project_code' => $projectCode]),
            'departments' => $departments,
            'managers' => $managers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_code' => 'required|string|unique:projects,project_code',
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'project_manager_id' => 'required|exists:users,id',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,on_hold',
        ]);

        Project::create($validated);
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $departments = Department::where('status', 'active')->get();
        $managers = User::where('status', 'active')->get();
        
        return view('projects.form', compact('project', 'departments', 'managers'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_code' => 'required|string|unique:projects,project_code,' . $project->id,
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'project_manager_id' => 'required|exists:users,id',
            'location' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,on_hold',
        ]);

        $project->update($validated);
        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['department', 'manager', 'auditEvents']);
        return view('projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
