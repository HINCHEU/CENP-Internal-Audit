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
            @include('audit-findings.partials.finding-type-badge', ['finding' => $finding])
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

    @php
        $submissionsByAuditor = $finding->auditEvent->findings->keyBy('user_id');
    @endphp

    <div class="pt-8 border-t border-slate-100">
        <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-4">Auditor Submission Status</h3>

        @if($finding->auditEvent->auditors->isEmpty())
            <p class="text-sm font-medium text-slate-500">No auditors assigned to this audit event.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-slate-100 rounded-xl">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider font-extrabold">
                            <th class="px-6 py-4">Auditor</th>
                            <th class="px-6 py-4">Submission Status</th>
                            <th class="px-6 py-4 text-right">Submitted On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($finding->auditEvent->auditors as $auditor)
                            @php
                                $submission = $submissionsByAuditor->get($auditor->id);
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode($auditor->name) }}&background=6366F1&color=fff" alt="{{ $auditor->name }}"/>
                                        <span class="text-sm font-bold text-slate-800">{{ $auditor->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200">
                                            <i class="ph ph-check-circle"></i> Submitted
                                        </span>
                                        @if($submission->edit_request_status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-amber-600 bg-amber-50 border border-amber-200 ml-2">Edit Requested</span>
                                        @elseif($submission->edit_request_status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-200 ml-2">Edit Approved</span>
                                        @elseif($submission->edit_request_status === 'rejected')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 ml-2">Edit Rejected</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200">
                                            <i class="ph ph-clock"></i> Not Submitted
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium text-slate-500">
                                    {{ $submission ? $submission->created_at->format('M d, Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="pt-8 border-t border-slate-100 mt-8">
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
