@extends('layouts.app')

@section('title', 'Evaluation Analytics - CE&P Internal Audit System')
@section('header', 'Evaluation Analytics')
@section('subheader', 'View scoring results and analytics for this evaluation.')

@php
    $gradeColorClass = match($overallGrade) {
        'A+' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'A' => 'bg-green-50 text-green-700 border-green-200',
        'B' => 'bg-blue-50 text-blue-700 border-blue-200',
        'F' => 'bg-red-50 text-red-700 border-red-200',
        default => 'bg-slate-50 text-slate-700 border-slate-200',
    };
@endphp

@section('content')
<div class="mb-6 flex items-start justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ $admin_evaluation->title }}</h2>
        @if($admin_evaluation->project)
            <p class="text-sm font-semibold text-indigo-600 mt-2"><i class="ph ph-briefcase"></i> Project: {{ $admin_evaluation->project->project_code }} - {{ $admin_evaluation->project->name }}</p>
        @endif
        @if($admin_evaluation->date)
            <p class="text-sm text-slate-500 mt-1"><i class="ph ph-calendar"></i> Date: {{ $admin_evaluation->date->format('M d, Y') }}</p>
        @endif
    </div>
    <div class="flex items-center gap-3">
        <button type="button" class="qr-trigger px-5 py-2.5 bg-sky-50 border border-sky-200 text-sky-600 rounded-xl hover:bg-sky-100 transition-colors font-bold flex items-center gap-2"
            data-qr-url="{{ url(route('user-evaluations.show', $admin_evaluation->id)) }}"
            data-event-title="{{ $admin_evaluation->title }}"
            data-event-id="{{ $admin_evaluation->id }}"
        >
            <i class="ph ph-qr-code text-lg"></i> View QR Code
        </button>
        <a href="{{ route('admin-evaluations.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-medium premium-shadow flex items-center gap-2">
            <i class="ph ph-arrow-left text-lg"></i> Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Final Score Card -->
    <div class="bg-gradient-primary rounded-3xl p-8 text-white premium-shadow relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10 mb-4">
                    <i class="ph ph-medal text-2xl text-white"></i>
                </div>
                <p class="text-indigo-100 font-medium uppercase tracking-wider text-xs mb-1">Overall Grade</p>
                <div class="flex items-center gap-4 mt-2">
                    <h3 class="text-5xl font-black">{{ number_format($finalScore, 1) }}<span class="text-2xl text-indigo-200">/100</span></h3>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border {{ $gradeColorClass }}">
                        Grade: <span class="text-lg">{{ $overallGrade }}</span>
                    </span>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-white/10 flex justify-between items-end">
                <p class="text-sm text-indigo-100">Calculated from {{ $totalVoices }} effective voices.</p>
            </div>
        </div>
    </div>

    <!-- In-house Stats -->
    <div class="bg-white rounded-3xl p-8 premium-shadow border border-slate-100 flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center border border-sky-100 mb-4">
                <i class="ph ph-users-three text-2xl text-sky-600"></i>
            </div>
            <p class="text-slate-500 font-medium uppercase tracking-wider text-xs mb-1">In-house Scores</p>
            <h3 class="text-4xl font-bold text-slate-800">{{ number_format($inhouseAverage, 1) }}</h3>
        </div>
        <div class="mt-6 pt-6 border-t border-slate-100 flex justify-between items-center">
            <p class="text-sm font-medium text-slate-500">{{ $inhouseScores->count() }} Submissions</p>
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase tracking-wider">Counts as 1 Voice</span>
        </div>
    </div>

    <!-- External Stats -->
    <div class="bg-white rounded-3xl p-8 premium-shadow border border-slate-100 flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center border border-emerald-100 mb-4">
                <i class="ph ph-globe text-2xl text-emerald-600"></i>
            </div>
            <p class="text-slate-500 font-medium uppercase tracking-wider text-xs mb-1">External Scores</p>
            <h3 class="text-4xl font-bold text-slate-800">{{ $externalScores->count() }}</h3>
        </div>
        <div class="mt-6 pt-6 border-t border-slate-100 flex justify-between items-center">
            <p class="text-sm font-medium text-slate-500">Individual Submissions</p>
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase tracking-wider">Each Counts as 1 Voice</span>
        </div>
    </div>
</div>

<!-- All Submissions Table -->
<div class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800">Detailed Submissions</h3>
    </div>

    @if(session('success'))
    <div class="mx-6 mt-4 px-5 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-medium flex items-center gap-2 text-sm">
        <i class="ph ph-check-circle text-lg"></i> {{ session('success') }}
    </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-slate-500 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-8 py-5">Evaluator</th>
                    <th class="px-6 py-5">Department</th>
                    <th class="px-6 py-5">Type</th>
                    <th class="px-6 py-5">Score</th>
                    <th class="px-8 py-5">Comment</th>
                    <th class="px-6 py-5 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($allScores as $score)
                @php $isExcluded = $score->excluded; @endphp
                <tr class="hover:bg-slate-50 transition-colors {{ $isExcluded ? 'opacity-50' : '' }}">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            @php
                                $displayName = $score->evaluator_name ?? ($score->user ? $score->user->name : 'Anonymous');
                            @endphp
                            <img class="h-8 w-8 rounded-full object-cover ring-2 ring-indigo-50" src="https://ui-avatars.com/api/?name={{ urlencode($displayName) }}&background=6366F1&color=fff" alt="" />
                            <div>
                                <p class="text-slate-800 font-bold text-sm">{{ $displayName }}</p>
                                @if($isExcluded)
                                    <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">Excluded</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="text-slate-600 text-sm font-medium">{{ $score->department ? $score->department->name : '-' }}</span>
                    </td>
                    <td class="px-6 py-5">
                        @if($score->evaluator_type === 'inhouse')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-sky-50 text-sky-600 border border-sky-200">
                                In-house
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200">
                                External
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <div class="inline-flex items-center justify-center w-15 h-10 rounded-md {{ $isExcluded ? 'bg-slate-100 text-slate-400 line-through border border-slate-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-100' }} font-bold">
                            {{ $score->score }}
                        </div>
                    </td>
                    <td class="px-8 py-5 text-slate-600 text-sm">
                        {{ $score->comment ?: '-' }}
                    </td>
                    <td class="px-6 py-5 text-center">
                        <form action="{{ route('admin-evaluations.scores.toggle-exclude', $score->id) }}" method="POST">
                            @csrf
                            @if($isExcluded)
                                <button type="submit" title="Include this score in analytics"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors whitespace-nowrap">
                                    <i class="ph ph-check-circle text-sm"></i> Include
                                </button>
                            @else
                                <button type="submit" title="Exclude this score from analytics"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 transition-colors whitespace-nowrap">
                                    <i class="ph ph-x-circle text-sm"></i> Exclude
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-8 text-center text-slate-500 font-medium">
                        No scores submitted yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('partials.qr-modal')
@endsection
