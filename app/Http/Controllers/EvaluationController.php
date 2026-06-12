<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = \App\Models\Evaluation::with(['project'])->withCount('scores')->latest()->paginate(10);
        return view('admin-evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $projects = \App\Models\Project::orderBy('name')->get();
        return view('admin-evaluations.form', ['evaluation' => new \App\Models\Evaluation(), 'projects' => $projects]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,inactive',
        ]);

        \App\Models\Evaluation::create($validated);
        return redirect()->route('admin-evaluations.index')->with('success', 'Evaluation created successfully.');
    }

    public function show(\App\Models\Evaluation $admin_evaluation)
    {
        $admin_evaluation->load(['scores.user']);
        
        $inhouseScores = $admin_evaluation->scores->where('evaluator_type', 'inhouse');
        $externalScores = $admin_evaluation->scores->where('evaluator_type', 'external');

        // Analytics calculation
        $inhouseAverage = $inhouseScores->count() > 0 ? $inhouseScores->avg('score') : 0;
        
        // Final score: inhouseAverage acts as 1 score, plus all external scores individually
        $totalExternalScore = $externalScores->sum('score');
        $totalVoices = ($inhouseScores->count() > 0 ? 1 : 0) + $externalScores->count();
        
        $finalScore = $totalVoices > 0 ? ($inhouseAverage + $totalExternalScore) / $totalVoices : 0;

        return view('admin-evaluations.show', compact('admin_evaluation', 'inhouseScores', 'externalScores', 'inhouseAverage', 'finalScore', 'totalVoices'));
    }

    public function edit(\App\Models\Evaluation $admin_evaluation)
    {
        $projects = \App\Models\Project::orderBy('name')->get();
        return view('admin-evaluations.form', ['evaluation' => $admin_evaluation, 'projects' => $projects]);
    }

    public function update(Request $request, \App\Models\Evaluation $admin_evaluation)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,inactive',
        ]);

        $admin_evaluation->update($validated);
        return redirect()->route('admin-evaluations.index')->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(\App\Models\Evaluation $admin_evaluation)
    {
        $admin_evaluation->delete();
        return redirect()->route('admin-evaluations.index')->with('success', 'Evaluation deleted successfully.');
    }
}
