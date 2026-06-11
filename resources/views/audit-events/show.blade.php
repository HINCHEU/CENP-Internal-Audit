@extends('layouts.app')

@section('title', 'View Audit Event - CE&P Internal Audit System')
@section('header', 'Audit Event Details')
@section('subheader', 'Detailed information about the audit event, submission progress, and findings.')

@section('content')
@php
    $eventDate = \Carbon\Carbon::parse($auditEvent->audit_date);
    $ringColor = $overallAverageScore >= 90 ? '#10b981' : ($overallAverageScore >= 70 ? '#f59e0b' : '#f43f5e');
    
    // Determine grade color and styling
    $gradeColorClass = match($overallGrade) {
        'A+' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'A' => 'bg-green-50 text-green-700 border-green-200',
        'B' => 'bg-blue-50 text-blue-700 border-blue-200',
        'F' => 'bg-red-50 text-red-700 border-red-200',
        default => 'bg-slate-50 text-slate-700 border-slate-200',
    };
@endphp

<div class="space-y-6">
    {{-- Hero header --}}
    <div class="relative overflow-hidden rounded-3xl premium-shadow border border-slate-100 bg-white">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-primary"></div>
        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 bottom-0 h-48 w-48 rounded-full bg-violet-500/5 blur-3xl pointer-events-none"></div>

        <div class="relative p-8 pb-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex items-start gap-4 min-w-0">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-primary text-white flex items-center justify-center shadow-lg shadow-indigo-500/25 shrink-0">
                        <i class="ph ph-calendar-check text-3xl"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest mb-1">Audit event</p>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight break-words">{{ $auditEvent->title }}</h2>
                        <p class="text-sm font-semibold text-slate-500 mt-1.5">EVT-{{ $auditEvent->id }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    @php $eventStatus = $auditEvent->submissionStatus(); @endphp
                    @if($eventStatus === 'completed')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Completed
                        </span>
                    @elseif($eventStatus === 'in_progress')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span> In Progress
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> Pending
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Score overview --}}
    <div class="rounded-3xl premium-shadow border border-slate-100 bg-white overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-slate-50/60">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                    <i class="ph ph-chart-bar text-xl"></i>
                </span>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Audit scores overview</h3>
                    <p class="text-xs font-medium text-slate-500">Average evaluation scores submitted by auditors for this event.</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            @if($scoredFindings === 0)
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 px-6 py-10 text-center">
                    <i class="ph ph-chart-bar text-4xl text-slate-300 mb-3 inline-block"></i>
                    <p class="text-slate-600 font-semibold">No scores recorded</p>
                    <p class="text-sm text-slate-500 mt-1">Audit scores will appear once auditors submit findings with evaluation scores.</p>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-10 items-center">
                    {{-- Overall ring --}}
                    <div class="xl:col-span-5 flex flex-col items-center text-center">
                        <div
                            class="relative h-44 w-44 rounded-full flex items-center justify-center shadow-inner"
                            style="background: conic-gradient({{ $ringColor }} {{ min($overallAverageScore * 1.11, 100) }}%, #e2e8f0 0);"
                            role="img"
                            aria-label="Overall average score {{ $overallAverageScore }} out of 100"
                        >
                            <div class="absolute inset-3 rounded-full bg-white flex flex-col items-center justify-center shadow-sm border border-slate-100">
                                <span class="text-4xl font-extrabold tabular-nums text-slate-900 leading-none">{{ $overallAverageScore }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-2">/ 100</span>
                            </div>
                        </div>
                        <div class="mt-5 flex flex-col items-center gap-3">
                            <div class="flex gap-3 items-center">
                                <p class="text-base font-bold text-slate-800">
                                    <span class="text-slate-400 font-semibold">Based on</span> {{ $scoredFindings }}
                                    <span class="text-slate-600 font-semibold">evaluation{{ $scoredFindings === 1 ? '' : 's' }}</span>
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border {{ $gradeColorClass }}">
                                Grade: <span class="text-lg">{{ $overallGrade }}</span>
                            </span>
                        </div>
                        <p class="text-xs font-medium text-slate-500 mt-3 max-w-xs">Average score across all audit findings submitted for this event.</p>
                    </div>

                    {{-- Department breakdown --}}
                    <div class="xl:col-span-7 space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                                    <i class="ph ph-buildings text-indigo-500"></i>
                                    By department
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">Average score within each department's submissions.</p>
                            </div>
                            @if(count($departmentScoreStats) > 1)
                                <div class="rounded-xl border border-indigo-100 bg-indigo-50/80 px-4 py-2.5 text-left sm:text-right">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Avg. of department scores</p>
                                    <p class="text-xl font-extrabold text-indigo-900 tabular-nums">{{ $departmentAverageScore }}/100</p>
                                </div>
                            @endif
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($departmentScoreStats as $dept)
                                @php
                                    $score = $dept['average_score'];
                                    $barClass = $score >= 90 ? 'bg-emerald-500' : ($score >= 70 ? 'bg-amber-500' : 'bg-rose-500');
                                @endphp
                                <div class="group rounded-2xl border border-slate-100 bg-slate-50/40 hover:bg-white hover:border-indigo-100 hover:shadow-md transition-all duration-300 p-4">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <p class="text-sm font-bold text-slate-800 leading-snug pr-2">{{ $dept['label'] }}</p>
                                        <span class="shrink-0 text-lg font-extrabold tabular-nums text-slate-900">{{ $score }}/100</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-200/80 overflow-hidden mb-2">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $barClass }}" style="width: {{ min($score * 1.11, 100) }}%"></div>
                                    </div>
                                    <p class="text-[11px] font-semibold text-slate-500">
                                        {{ $dept['count'] }} evaluation{{ $dept['count'] === 1 ? '' : 's' }} submitted
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Event details --}}
    <div class="rounded-3xl premium-shadow border border-slate-100 bg-white p-8">
        <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-6 flex items-center gap-2">
            <i class="ph ph-info text-indigo-500"></i> Event details
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Description</p>
                <p class="text-[15px] font-semibold text-slate-800 leading-relaxed">{{ $auditEvent->description ?? 'No description provided.' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Project</p>
                @if($auditEvent->project)
                    <a href="{{ route('projects.show', $auditEvent->project->id) }}" class="text-[15px] font-bold text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center gap-1.5">
                        {{ $auditEvent->project->name }}
                        <i class="ph ph-arrow-square-out text-base opacity-70"></i>
                    </a>
                @else
                    <p class="text-[15px] font-semibold text-slate-400">—</p>
                @endif
            </div>
            <div class="space-y-1">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Schedule</p>
                <p class="text-[15px] font-semibold text-slate-800 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-indigo-600">
                        <i class="ph ph-calendar-blank"></i>
                        {{ $eventDate->format('M d, Y') }}
                    </span>
                    @if($auditEvent->audit_time)
                        <span class="text-slate-300">·</span>
                        <span class="inline-flex items-center gap-1.5 text-slate-600">
                            <i class="ph ph-clock text-slate-400"></i>
                            {{ \Carbon\Carbon::parse($auditEvent->audit_time)->format('h:i A') }}
                        </span>
                    @endif
                </p>
            </div>
            <div class="space-y-2">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Assigned auditors</p>
                <div class="flex flex-wrap gap-3">
                    @forelse($auditEvent->auditors as $auditor)
                        <div class="flex items-center gap-2.5 pl-1 pr-3 py-1.5 rounded-full bg-slate-50 border border-slate-100">
                            <img class="h-9 w-9 rounded-full ring-2 ring-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($auditor->name) }}&background=6366F1&color=fff" alt="" />
                            <div class="text-left min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate max-w-[140px]">{{ $auditor->name }}</p>
                                <p class="text-[10px] font-semibold text-slate-500 truncate max-w-[140px]">{{ $auditor->department->name ?? 'No department' }}</p>
                            </div>
                        </div>
                    @empty
                        <span class="text-sm font-medium text-slate-400">No auditors assigned.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Findings --}}
    <div class="rounded-3xl premium-shadow border border-slate-100 bg-white overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3 bg-white">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-500">
                <i class="ph ph-warning-circle text-xl"></i>
            </span>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Audit findings</h3>
                <p class="text-xs font-medium text-slate-500">{{ $auditEvent->findings->count() }} recorded</p>
            </div>
        </div>

        <div class="p-8 pt-6">
            @if($auditEvent->findings->isEmpty())
                <p class="text-slate-500 text-sm font-medium py-4">No findings recorded for this audit event yet.</p>
            @else
                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-50/90 text-slate-500 text-[10px] uppercase tracking-wider font-extrabold">
                                <th class="px-6 py-4">Finding</th>
                                <th class="px-6 py-4">Type</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Logged</th>
                                <th class="px-6 py-4 text-center w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($auditEvent->findings as $finding)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('audit-findings.show', $finding->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm line-clamp-1 max-w-[280px] block transition-colors">
                                        {{ explode("\n", $finding->description)[0] }}
                                    </a>
                                    <p class="text-[10px] font-semibold text-slate-500 mt-0.5">By {{ $finding->auditor->name ?? 'Unknown' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @include('audit-findings.partials.finding-type-badge', ['finding' => $finding, 'compact' => true])
                                </td>
                                <td class="px-6 py-4">
                                    @if($finding->status == 'open')
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold text-rose-600 bg-rose-50">Open</span>
                                    @elseif($finding->status == 'resolved')
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold text-amber-600 bg-amber-50">Resolved</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50">Closed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-semibold text-slate-500 whitespace-nowrap">
                                    {{ $finding->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('audit-findings.show', $finding->id) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors" title="View">
                                        <i class="ph ph-eye text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 pb-2">
        <a href="{{ route('audit-events.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl font-bold transition-colors premium-shadow">
            <i class="ph ph-arrow-left text-lg"></i> Back to list
        </a>
        <a href="{{ route('audit-events.edit', $auditEvent->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary hover:opacity-95 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/25">
            <i class="ph ph-pencil-simple text-lg"></i> Edit event
        </a>
    </div>
</div>
@endsection
