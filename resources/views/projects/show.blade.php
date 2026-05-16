@extends('layouts.app')

@section('title', 'View Project - CE&P Internal Audit System')
@section('header', 'Project Details')
@section('subheader', 'Detailed information about the project and its audits.')

@section('content')
<div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
    <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-6">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
            <i class="ph ph-folder text-3xl"></i>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800">{{ $project->name }}</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">{{ $project->project_code }}</p>
        </div>
        
        <div class="ml-auto">
            @if($project->status === 'active')
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                </span>
            @elseif($project->status === 'on_hold')
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-amber-50 text-amber-600 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> On Hold
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Completed
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Department</h3>
            <p class="text-base font-semibold text-slate-800">{{ $project->department->name ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Project Manager</h3>
            <div class="flex items-center gap-2">
                @if($project->manager)
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($project->manager->name) }}&background=E2E8F0&color=475569" class="w-6 h-6 rounded-full" alt="PM">
                    <span class="text-slate-800 font-semibold text-base">{{ $project->manager->name }}</span>
                @else
                    <span class="text-slate-500 font-semibold text-base">-</span>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Location</h3>
            <p class="text-base font-semibold text-slate-800">{{ $project->location ?? '-' }}</p>
        </div>
        
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Timeline</h3>
            <p class="text-base font-semibold text-slate-800">
                {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'N/A' }} - 
                {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'N/A' }}
            </p>
        </div>
    </div>

    <div class="pt-8 border-t border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="ph ph-calendar text-indigo-500"></i> Audit Events
        </h3>
        
        @if($project->auditEvents->isEmpty())
            <p class="text-slate-500 text-sm font-medium">No audit events scheduled for this project.</p>
        @else
            <div class="space-y-4 mt-4">
                @foreach($project->auditEvents as $event)
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <a href="{{ route('audit-events.show', $event->id) }}" class="text-indigo-600 font-bold hover:underline">{{ $event->title }}</a>
                        <p class="text-xs text-slate-500 font-medium mt-1">
                            {{ \Carbon\Carbon::parse($event->audit_date)->format('M d, Y') }} 
                            @if($event->audit_time) at {{ \Carbon\Carbon::parse($event->audit_time)->format('h:i A') }} @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="pt-8 border-t border-slate-100 mt-8 flex items-center justify-start gap-4">
        <a href="{{ route('projects.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Back to List</a>
        <a href="{{ route('projects.edit', $project->id) }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-colors shadow-sm">Edit Project</a>
    </div>
</div>
@endsection
