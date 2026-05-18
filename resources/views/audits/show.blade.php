@extends('layouts.app')

@section('title', 'My Submission - CE&P Internal Audit System')
@section('header', 'Your Audit Submission')
@section('subheader', $auditEvent->title . ' — ' . ($auditEvent->project->name ?? 'No Project'))

@section('content')
@php
    $score = $finding->parsedScore();
    $description = $finding->parsedDescription();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0">
                        <i class="ph ph-check-circle text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-800">Submitted on {{ $finding->created_at->format('M d, Y') }}</h2>
                        <p class="text-sm font-medium text-slate-500 mt-1">at {{ $finding->created_at->format('h:i A') }}</p>
                    </div>
                </div>
                @if($finding->edit_request_status === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-amber-50 text-amber-600 border border-amber-200">
                        <i class="ph ph-hourglass-high"></i> Edit request pending
                    </span>
                @elseif($finding->edit_request_status === 'approved')
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
                        <i class="ph ph-pencil-simple"></i> Edit approved — you may resubmit
                    </span>
                @elseif($finding->edit_request_status === 'rejected')
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-200">
                        <i class="ph ph-x-circle"></i> Edit request rejected
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Evaluation Score</h3>
                    @if($score !== null)
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-extrabold text-xl
                                @if($score >= 90) bg-emerald-100 text-emerald-700
                                @elseif($score >= 70) bg-amber-100 text-amber-700
                                @else bg-rose-100 text-rose-700 @endif
                            ">
                                {{ $score }}
                            </div>
                            <span class="text-sm font-bold text-slate-600">out of 100</span>
                        </div>
                    @else
                        <p class="text-sm font-medium text-slate-500">—</p>
                    @endif
                </div>
                <div>
                    <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Finding Classification</h3>
                    @include('audit-findings.partials.finding-type-badge', ['finding' => $finding])
                </div>
            </div>

            <div>
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Findings & Comments</h3>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-6">
                    <p class="text-sm font-medium text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $description }}</p>
                </div>
            </div>

            @if($finding->evidence_file_path)
            <div class="mt-8 pt-8 border-t border-slate-100">
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Evidence & Attachments</h3>
                <a href="{{ Storage::url($finding->evidence_file_path) }}" target="_blank" class="inline-flex items-center gap-3 px-5 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl font-bold transition-colors border border-indigo-100">
                    <i class="ph ph-file-arrow-down text-xl"></i> View / Download Evidence
                </a>
            </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <a href="{{ route('audits.index') }}" class="px-6 py-3.5 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl font-bold transition-all shadow-sm text-center">
                Back to My Audits
            </a>
            @if($finding->edit_request_status === 'approved')
                <a href="{{ route('audits.submit', $auditEvent->id) }}" class="px-6 py-3.5 text-white bg-amber-500 hover:bg-amber-600 rounded-xl font-bold transition-all shadow-lg shadow-amber-500/30 text-center flex items-center justify-center gap-2">
                    <i class="ph ph-arrow-counter-clockwise"></i> Resubmit Audit
                </a>
            @elseif($finding->edit_request_status !== 'pending')
                <form action="{{ route('audits.request-edit', $auditEvent->id) }}" method="POST" class="sm:ml-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-white border-2 border-slate-200 hover:border-indigo-500 hover:text-indigo-600 text-slate-600 font-bold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                        <i class="ph ph-pencil-simple"></i> Request Edit
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="space-y-8">
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full blur-3xl"></div>
            <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-6 relative z-10">Event Information</h3>
            <ul class="space-y-6 relative z-10">
                <li class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="ph ph-briefcase text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Project</p>
                        <p class="text-sm font-bold text-slate-800">{{ $auditEvent->project->name ?? 'None' }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        <i class="ph ph-calendar text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Date & Time</p>
                        <p class="text-sm font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($auditEvent->audit_date)->format('M d, Y') }}<br>
                            @if($auditEvent->audit_time) {{ \Carbon\Carbon::parse($auditEvent->audit_time)->format('h:i A') }} @else TBD @endif
                        </p>
                    </div>
                </li>
                @if($auditEvent->description)
                <li class="flex items-start gap-4 pt-2 border-t border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center shrink-0">
                        <i class="ph ph-note text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Event Description</p>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed">{{ $auditEvent->description }}</p>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
