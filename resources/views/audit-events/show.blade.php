@extends('layouts.app')

@section('title', 'View Audit Event - CE&P Internal Audit System')
@section('header', 'Audit Event Details')
@section('subheader', 'Detailed information about the audit event and its findings.')

@section('content')
<div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-6 border-b border-slate-100 pb-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                <i class="ph ph-calendar text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800">{{ $auditEvent->title }}</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">EVT-{{ $auditEvent->id }}</p>
            </div>
        </div>
        
        <div>
            @php
                $eventDate = \Carbon\Carbon::parse($auditEvent->audit_date);
                $now = \Carbon\Carbon::now();
            @endphp
            
            @if($eventDate->isFuture())
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    Pending
                </span>
            @elseif($eventDate->isToday())
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span> Today
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Past
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Description</h3>
            <p class="text-base font-semibold text-slate-800">{{ $auditEvent->description ?? 'No description provided.' }}</p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Project</h3>
            @if($auditEvent->project)
                <a href="{{ route('projects.show', $auditEvent->project->id) }}" class="text-base font-bold text-indigo-600 hover:underline">{{ $auditEvent->project->name }}</a>
            @else
                <p class="text-base font-semibold text-slate-500">-</p>
            @endif
        </div>
        
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Schedule</h3>
            <p class="text-base font-semibold text-slate-800">
                {{ \Carbon\Carbon::parse($auditEvent->audit_date)->format('M d, Y') }} 
                @if($auditEvent->audit_time)
                    at {{ \Carbon\Carbon::parse($auditEvent->audit_time)->format('h:i A') }}
                @endif
            </p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Assigned Auditors</h3>
            <div class="flex -space-x-3 overflow-hidden mt-2">
                @foreach($auditEvent->auditors as $auditor)
                    <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode($auditor->name) }}&background=6366F1&color=fff" alt="{{ $auditor->name }}" title="{{ $auditor->name }}"/>
                @endforeach
                @if($auditEvent->auditors->isEmpty())
                    <span class="text-slate-400 text-sm font-medium">No auditors assigned.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="pt-8 border-t border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="ph ph-warning-circle text-rose-500"></i> Audit Findings
        </h3>
        
        @if($auditEvent->findings->isEmpty())
            <p class="text-slate-500 text-sm font-medium">No findings recorded for this audit event yet.</p>
        @else
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left border-collapse border border-slate-100 rounded-xl">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider font-extrabold">
                            <th class="px-6 py-4">Finding Details</th>
                            <th class="px-6 py-4">Severity</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Date Logged</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($auditEvent->findings as $finding)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-slate-800 font-bold text-sm line-clamp-1 max-w-[300px]">{{ explode("\n", $finding->description)[0] }}</p>
                                <p class="text-[11px] font-semibold text-slate-500 mt-0.5">By {{ $finding->auditor->name ?? 'Unknown' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if(str_contains(strtolower($finding->finding_type), 'major'))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">Major</span>
                                @elseif(str_contains(strtolower($finding->finding_type), 'minor'))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200">Minor</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-200">Obsv</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($finding->status == 'open')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-rose-600 bg-rose-50">Open</span>
                                @elseif($finding->status == 'resolved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-amber-600 bg-amber-50">Resolved</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-emerald-600 bg-emerald-50">Closed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-slate-500">
                                {{ $finding->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="pt-8 border-t border-slate-100 mt-8 flex items-center justify-start gap-4">
        <a href="{{ route('audit-events.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Back to List</a>
        <a href="{{ route('audit-events.edit', $auditEvent->id) }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-colors shadow-sm">Edit Audit Event</a>
    </div>
</div>
@endsection
