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

    public function analyticByUser()
    {
        $evaluations = \App\Models\Evaluation::orderBy('created_at', 'asc')->get();
        
        $users = $this->getEvaluatorsWithScores();

        return view('admin-evaluations.analytic-user', compact('evaluations', 'users'));
    }

    public function analyticByProject()
    {
        $projects = \App\Models\Project::orderBy('name')->get();
        
        $evaluations = \App\Models\Evaluation::with('scores')->orderBy('date', 'asc')->get();
        
        $dates = $evaluations->pluck('date')->filter()->unique()->sort()->values();
        
        $evaluationScores = [];
        foreach ($evaluations as $evaluation) {
            $inhouseScores = $evaluation->scores->where('evaluator_type', 'inhouse');
            $externalScores = $evaluation->scores->where('evaluator_type', 'external');
            $inhouseAverage = $inhouseScores->count() > 0 ? $inhouseScores->avg('score') : 0;
            $totalExternalScore = $externalScores->sum('score');
            $totalVoices = ($inhouseScores->count() > 0 ? 1 : 0) + $externalScores->count();
            $finalScore = $totalVoices > 0 ? ($inhouseAverage + $totalExternalScore) / $totalVoices : null;
            
            $evaluationScores[$evaluation->id] = $finalScore;
        }

        $projectScores = [];
        foreach ($projects as $project) {
            $projectScores[$project->id] = [];
            foreach ($dates as $date) {
                $dateKey = $date->format('Y-m-d');
                $projectEvals = $evaluations->where('project_id', $project->id)->filter(function($e) use ($dateKey) {
                    return $e->date && $e->date->format('Y-m-d') === $dateKey;
                });
                
                if ($projectEvals->count() > 0) {
                    $totalScore = 0;
                    $validEvals = 0;
                    foreach ($projectEvals as $eval) {
                        $score = $evaluationScores[$eval->id];
                        if ($score !== null) {
                            $totalScore += $score;
                            $validEvals++;
                        }
                    }
                    $projectScores[$project->id][$dateKey] = $validEvals > 0 ? $totalScore / $validEvals : null;
                } else {
                    $projectScores[$project->id][$dateKey] = null;
                }
            }
        }

        return view('admin-evaluations.analytic-project', compact('projects', 'dates', 'projectScores'));
    }

    public function exportAnalyticByUser()
    {
        $evaluations = \App\Models\Evaluation::orderBy('created_at', 'asc')->get();
        
        $users = $this->getEvaluatorsWithScores();

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AnalyticUserExport($evaluations, $users), 'Analytic-by-User.xlsx');
    }

    public function exportAnalyticByProject()
    {
        $projects = \App\Models\Project::orderBy('name')->get();
        
        $evaluations = \App\Models\Evaluation::with('scores')->orderBy('date', 'asc')->get();
        
        $dates = $evaluations->pluck('date')->filter()->unique()->sort()->values();
        
        $evaluationScores = [];
        foreach ($evaluations as $evaluation) {
            $inhouseScores = $evaluation->scores->where('evaluator_type', 'inhouse');
            $externalScores = $evaluation->scores->where('evaluator_type', 'external');
            $inhouseAverage = $inhouseScores->count() > 0 ? $inhouseScores->avg('score') : 0;
            $totalExternalScore = $externalScores->sum('score');
            $totalVoices = ($inhouseScores->count() > 0 ? 1 : 0) + $externalScores->count();
            $finalScore = $totalVoices > 0 ? ($inhouseAverage + $totalExternalScore) / $totalVoices : null;
            
            $evaluationScores[$evaluation->id] = $finalScore;
        }

        $projectScores = [];
        foreach ($projects as $project) {
            $projectScores[$project->id] = [];
            foreach ($dates as $date) {
                $dateKey = $date->format('Y-m-d');
                $projectEvals = $evaluations->where('project_id', $project->id)->filter(function($e) use ($dateKey) {
                    return $e->date && $e->date->format('Y-m-d') === $dateKey;
                });
                
                if ($projectEvals->count() > 0) {
                    $totalScore = 0;
                    $validEvals = 0;
                    foreach ($projectEvals as $eval) {
                        $score = $evaluationScores[$eval->id];
                        if ($score !== null) {
                            $totalScore += $score;
                            $validEvals++;
                        }
                    }
                    $projectScores[$project->id][$dateKey] = $validEvals > 0 ? $totalScore / $validEvals : null;
                } else {
                    $projectScores[$project->id][$dateKey] = null;
                }
            }
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AnalyticProjectExport($projects, $dates, $projectScores), 'Analytic-by-Project.xlsx');
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
        $admin_evaluation->load(['scores.user', 'scores.department']);
        
        // All scores for display in the table (including excluded)
        $allScores = $admin_evaluation->scores;

        // Only non-excluded scores count toward analytics
        $activeScores = $allScores->where('excluded', false);

        $inhouseScores = $activeScores->where('evaluator_type', 'inhouse');
        $externalScores = $activeScores->where('evaluator_type', 'external');

        // Analytics calculation (excluded scores are ignored)
        $inhouseAverage = $inhouseScores->count() > 0 ? $inhouseScores->avg('score') : 0;
        
        $totalExternalScore = $externalScores->sum('score');
        $totalVoices = ($inhouseScores->count() > 0 ? 1 : 0) + $externalScores->count();
        
        $finalScore = $totalVoices > 0 ? ($inhouseAverage + $totalExternalScore) / $totalVoices : 0;
        
        $overallGrade = $admin_evaluation->calculateGrade($finalScore);

        return view('admin-evaluations.show', compact('admin_evaluation', 'allScores', 'inhouseScores', 'externalScores', 'inhouseAverage', 'finalScore', 'totalVoices', 'overallGrade'));
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

    public function toggleExcludeScore(\App\Models\EvaluationScore $score)
    {
        $score->update(['excluded' => !$score->excluded]);
        $message = $score->excluded ? 'Score excluded from analytics.' : 'Score included in analytics.';
        return redirect()->back()->with('success', $message);
    }

    private function getEvaluatorsWithScores()
    {
        // Registered users who have scores
        $users = \App\Models\User::with(['department', 'evaluationScores.department'])
            ->whereHas('evaluationScores')
            ->get();

        // Guest scores (submitted without logging in, user_id is null)
        $guestScores = \App\Models\EvaluationScore::with('department')
            ->whereNull('user_id')
            ->get()
            ->groupBy('evaluator_name');

        $guestUsers = collect();
        foreach ($guestScores as $name => $scores) {
            $guestUser = new \App\Models\User();
            $guestUser->name = $name;
            $guestUser->role = 'guest';
            $guestUser->gender = null;
            $guestUser->setRelation('department', $scores->first()->department);
            $guestUser->setRelation('evaluationScores', $scores);
            $guestUsers->push($guestUser);
        }

        return $users->concat($guestUsers)
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }
}
