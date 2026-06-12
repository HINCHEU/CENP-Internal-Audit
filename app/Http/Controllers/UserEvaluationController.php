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
        // Check if user already submitted a score
        $existingScore = \App\Models\EvaluationScore::where('evaluation_id', $evaluation->id)
            ->where('user_id', auth()->id())
            ->first();

        return view('user-evaluations.show', compact('evaluation', 'existingScore'));
    }

    public function storeScore(Request $request, \App\Models\Evaluation $evaluation)
    {
        $request->validate([
            'evaluator_type' => 'required|in:inhouse,external',
            'score' => 'required|integer|min:0|max:100',
            'comment' => 'nullable|string',
        ]);

        // Prevent multiple submissions
        $existingScore = \App\Models\EvaluationScore::where('evaluation_id', $evaluation->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingScore) {
            return redirect()->back()->with('error', 'You have already submitted a score for this evaluation.');
        }

        \App\Models\EvaluationScore::create([
            'evaluation_id' => $evaluation->id,
            'user_id' => auth()->id(),
            'evaluator_type' => $request->evaluator_type,
            'score' => $request->score,
            'comment' => $request->comment,
        ]);

        return redirect()->route('user-evaluations.index')->with('success', 'Your score has been submitted successfully.');
    }
}
