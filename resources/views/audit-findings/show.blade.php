@extends('layouts.app')

@section('title', 'View Audit Finding - CE&P Internal Audit System')
@section('header', 'Audit Finding Details')
@section('subheader', 'Detailed information about the submitted audit finding.')

@section('content')
<div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-6 border-b border-slate-100 pb-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                <i class="ph ph-file-text text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800">Finding for {{ $finding->auditEvent->title }}</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Submitted by {{ $finding->auditor->name }} on {{ $finding->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        
        <div>
            @if($finding->status == 'open')
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-200">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Open
                </span>
            @elseif($finding->status == 'resolved')
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-amber-50 text-amber-600 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Resolved
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Closed
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Finding Classification</h3>
            @if(str_contains(strtolower($finding->finding_type), 'major'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-rose-50 text-rose-600 border border-rose-200">Major Non-conformance</span>
            @elseif(str_contains(strtolower($finding->finding_type), 'minor'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-amber-50 text-amber-600 border border-amber-200">Minor Non-conformance</span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-50 text-blue-600 border border-blue-200">Observation</span>
            @endif
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Evaluation Score</h3>
            @if($parsedScore)
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm
                        @if((int)$parsedScore >= 90) bg-emerald-100 text-emerald-700
                        @elseif((int)$parsedScore >= 70) bg-amber-100 text-amber-700
                        @else bg-rose-100 text-rose-700 @endif
                    ">
                        {{ $parsedScore }}
                    </div>
                    <span class="text-sm font-bold text-slate-700">/ 100</span>
                </div>
            @else
                <p class="text-base font-semibold text-slate-500">-</p>
            @endif
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Project</h3>
            @if($finding->auditEvent->project)
                <a href="{{ route('projects.show', $finding->auditEvent->project->id) }}" class="text-base font-bold text-indigo-600 hover:underline">{{ $finding->auditEvent->project->name }}</a>
            @else
                <p class="text-base font-semibold text-slate-500">-</p>
            @endif
        </div>
        
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Audit Event</h3>
            <a href="{{ route('audit-events.show', $finding->auditEvent->id) }}" class="text-base font-bold text-indigo-600 hover:underline">{{ $finding->auditEvent->title }}</a>
        </div>
    </div>

    <div class="pt-8 border-t border-slate-100">
        <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-4">Detailed Description</h3>
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-6">
            <p class="text-sm font-medium text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $parsedDescription }}</p>
        </div>
    </div>
    
    @if($finding->evidence_file_path)
    <div class="pt-8 border-t border-slate-100 mt-8">
        <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-4">Attached Evidence</h3>
        <a href="{{ Storage::url($finding->evidence_file_path) }}" target="_blank" class="inline-flex items-center gap-3 px-5 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl font-bold transition-colors border border-indigo-100">
            <i class="ph ph-file-arrow-down text-xl"></i> View / Download Evidence Attachment
        </a>
    </div>
    @endif

    <div class="pt-8 border-t border-slate-100 mt-8 flex items-center justify-start gap-4">
        <a href="{{ route('reports.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Back to Reports</a>
    </div>
</div>
@endsection
