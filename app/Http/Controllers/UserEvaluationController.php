<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserEvaluationController extends Controller
{
    public function index()
    {
        $userScoreEvaluationIds = \App\Models\EvaluationScore::where('user_id', auth()->id())
            ->pluck('evaluation_id')
            ->toArray();

        $query = \App\Models\Evaluation::where('status', 'active');

        if (auth()->user()->role !== 'admin') {
            $query->whereIn('id', $userScoreEvaluationIds);
        }

        $evaluations = $query->latest()->paginate(12);
            
        return view('user-evaluations.index', compact('evaluations', 'userScoreEvaluationIds'));
    }

    public function show(\App\Models\Evaluation $evaluation)
    {
        // Check if user already submitted a score (if logged in)
        $existingScore = null;
        if (auth()->check()) {
            $existingScore = \App\Models\EvaluationScore::where('evaluation_id', $evaluation->id)
                ->where('user_id', auth()->id())
                ->first();
        }

        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        if (!$isAdmin && ! $existingScore && $evaluation->status !== 'active') {
            abort(403);
        }

        $departments = \App\Models\Department::all();

        return view('user-evaluations.show', compact('evaluation', 'existingScore', 'departments'));
    }

    public function storeScore(Request $request, \App\Models\Evaluation $evaluation)
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        if (!$isAdmin && $evaluation->status !== 'active') {
            abort(403);
        }

        $request->validate([
            'evaluator_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'evaluator_type' => 'required|in:inhouse,external',
            'score' => 'required|numeric|min:0|max:100|decimal:0,2',
            'comment' => 'nullable|string',
        ]);

        // Prevent multiple submissions if logged in
        if (auth()->check()) {
            $existingScore = \App\Models\EvaluationScore::where('evaluation_id', $evaluation->id)
                ->where('user_id', auth()->id())
                ->first();

            if ($existingScore) {
                return redirect()->back()->with('error', 'You have already submitted a score for this evaluation.');
            }
        }

        \App\Models\EvaluationScore::create([
            'evaluation_id' => $evaluation->id,
            'user_id' => auth()->id(),
            'department_id' => $request->department_id,
            'evaluator_name' => $request->evaluator_name,
            'evaluator_type' => $request->evaluator_type,
            'score' => $request->score,
            'comment' => $request->comment,
        ]);

        return redirect()->route('user-evaluations.thank-you', $evaluation->id)->with('success', 'Your score has been submitted successfully.');
    }
    public function thankYou(\App\Models\Evaluation $evaluation)
    {
        return view('user-evaluations.thank-you', compact('evaluation'));
    }
}
