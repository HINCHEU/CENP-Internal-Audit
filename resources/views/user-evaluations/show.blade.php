@extends('layouts.app')

@section('title', 'Submit Score - CE&P Internal Audit System')
@section('header', 'Submit Score')
@section('subheader', 'Evaluate and provide feedback for this project.')

@section('content')
<div class="mb-6">
    <a href="{{ route('user-evaluations.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold transition-colors">
        <i class="ph ph-arrow-left text-lg"></i> Back to Evaluations
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Evaluation Details -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100 mb-6">
                <i class="ph ph-star-half text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-3">{{ $evaluation->title }}</h2>
            <p class="text-slate-600 font-medium leading-relaxed">{{ $evaluation->description ?: 'No detailed description provided for this evaluation.' }}</p>
            
            <div class="mt-8 pt-8 border-t border-slate-100">
                <div class="flex items-center gap-3 text-slate-500 font-medium mb-3">
                    <i class="ph ph-calendar-blank text-lg"></i>
                    <span>Created: {{ $evaluation->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center gap-3 text-slate-500 font-medium">
                    <i class="ph ph-check-circle text-lg"></i>
                    <span>Status: <span class="capitalize">{{ $evaluation->status }}</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scoring Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-12">
            @if($existingScore)
                <div class="text-center py-10">
                    <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center border border-emerald-100 mx-auto mb-6">
                        <i class="ph ph-check-circle text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Score Submitted</h3>
                    <p class="text-slate-500 font-medium max-w-md mx-auto mb-8">You have already submitted your evaluation for this project. Thank you for your feedback.</p>
                    
                    <div class="bg-slate-50 rounded-2xl p-6 text-left max-w-md mx-auto">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Your Score</span>
                            <span class="text-2xl font-black text-indigo-600">{{ $existingScore->score }}<span class="text-sm text-indigo-300">/100</span></span>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-1">Evaluator Name</span>
                            <span class="text-slate-800 font-bold">{{ $existingScore->evaluator_name ?? 'Anonymous' }}</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-1">Department</span>
                            <span class="text-slate-800 font-medium">{{ $existingScore->department ? $existingScore->department->name : '-' }}</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-1">Evaluator Type</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-200 text-slate-700">
                                {{ $existingScore->evaluator_type }}
                            </span>
                        </div>
                        @if($existingScore->comment)
                        <div>
                            <span class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-1">Comment</span>
                            <p class="text-slate-700 text-sm bg-white p-4 rounded-xl border border-slate-100">{{ $existingScore->comment }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <form action="{{ route('user-evaluations.score', $evaluation->id) }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Submit Your Evaluation</h3>
                        <p class="text-sm font-medium text-slate-500">Please provide your honest score and feedback.</p>
                    </div>

                    <!-- Evaluator Name -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Your Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="evaluator_name" value="{{ old('evaluator_name', auth()->user()->name ?? '') }}" required placeholder="Enter your full name" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 outline-none shadow-sm">
                        @error('evaluator_name')<p class="text-rose-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    <!-- Department -->
                    <div class="relative">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Department <span class="text-rose-500">*</span></label>
                        <select name="department_id" required class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 outline-none shadow-sm appearance-none">
                            <option value="">Select your department...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', auth()->user()->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-[38px] pointer-events-none text-slate-400">
                            <i class="ph ph-caret-down text-lg"></i>
                        </div>
                        @error('department_id')<p class="text-rose-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    <!-- Evaluator Type -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Evaluator Type <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 premium-shadow focus:outline-none border-slate-200 hover:bg-slate-50 transition-colors">
                                <input type="radio" name="evaluator_type" value="inhouse" class="peer sr-only" required {{ old('evaluator_type') == 'inhouse' ? 'checked' : '' }}>
                                <div class="flex w-full items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center">
                                            <i class="ph ph-buildings text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">In-house</p>
                                            <p class="text-xs text-slate-500">Internal of Project</p>
                                        </div>
                                    </div>
                                    <i class="ph ph-check-circle text-2xl text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                                <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-indigo-600 pointer-events-none"></div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 premium-shadow focus:outline-none border-slate-200 hover:bg-slate-50 transition-colors">
                                <input type="radio" name="evaluator_type" value="external" class="peer sr-only" required {{ old('evaluator_type') == 'external' ? 'checked' : '' }}>
                                <div class="flex w-full items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                            <i class="ph ph-globe text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">External</p>
                                            <p class="text-xs text-slate-500">Outside perspective</p>
                                        </div>
                                    </div>
                                    <i class="ph ph-check-circle text-2xl text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                                <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-indigo-600 pointer-events-none"></div>
                            </label>
                        </div>
                        @error('evaluator_type')<p class="text-rose-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    <!-- Score Input -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Score (0-100) <span class="text-rose-500">*</span></label>
                        <div class="relative max-w-xs">
                            <input type="number" name="score" min="0" max="100" value="{{ old('score') }}" required class="w-full pl-5 pr-12 py-4 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-black text-2xl text-slate-800 outline-none shadow-sm text-center">
                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold">/100</span>
                            </div>
                        </div>
                        @error('score')<p class="text-rose-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    <!-- Comment -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Comments or Feedback</label>
                        <textarea name="comment" rows="4" placeholder="Share your thoughts on the evaluation..." class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">{{ old('comment') }}</textarea>
                        @error('comment')<p class="text-rose-500 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-4 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2 premium-hover w-full sm:w-auto justify-center">
                            <i class="ph ph-paper-plane-tilt text-xl"></i> Submit Evaluation
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
