@extends('layouts.app')

@section('title', 'Schedule Audit - CE&P Internal Audit System')
@section('header', 'Schedule Audit Event')
@section('subheader', 'Create a new audit event and assign auditors.')

@section('content')
<div class="max-w-4xl bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-12">
    <form action="{{ $auditEvent->exists ? route('audit-events.update', $auditEvent->id) : route('audit-events.store') }}" method="POST" class="space-y-8">
        @csrf
        @if($auditEvent->exists)
            @method('PUT')
        @endif

        <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                <i class="ph ph-calendar-plus text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Event Scheduling</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Set the time, project, and assign auditors.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Event Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Audit Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $auditEvent->title) }}" placeholder="e.g. Annual Security Review" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                @error('title')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Project -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Project <span class="text-rose-500">*</span></label>
                <select name="project_id" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    <option value="">Select associated project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $auditEvent->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->project_code }} - {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Schedule -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Audit Date <span class="text-rose-500">*</span></label>
                <input type="date" name="audit_date" value="{{ old('audit_date', $auditEvent->audit_date) }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 text-sm">
                @error('audit_date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Audit Time</label>
                <input type="time" name="audit_time" value="{{ old('audit_time', $auditEvent->audit_time ? \Carbon\Carbon::parse($auditEvent->audit_time)->format('H:i') : null) }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 text-sm">
                @error('audit_time')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Assigned Auditors -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Assign Auditors <span class="text-rose-500">*</span></label>
                @php
                    $selectedAuditors = old('auditor_ids', $auditEvent->auditors->pluck('id')->toArray());
                @endphp
                <select name="auditor_ids[]" multiple class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 h-40">
                    @foreach($auditors as $auditor)
                        <option value="{{ $auditor->id }}" {{ in_array($auditor->id, $selectedAuditors) ? 'selected' : '' }}>
                            {{ $auditor->name }} ({{ $auditor->department->name ?? 'No Dept' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-wider">Hold Ctrl (Windows) or Cmd (Mac) to select multiple auditors.</p>
                @error('auditor_ids')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description / Objectives</label>
                <textarea name="description" rows="4" placeholder="Describe the objectives and scope of this audit..." class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">{{ old('description', $auditEvent->description) }}</textarea>
                @error('description')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

        </div>

        <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4 mt-10">
            <a href="{{ route('audit-events.index') }}" class="px-6 py-3.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-800 rounded-xl font-bold transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3.5 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2 premium-hover">
                Save Event
            </button>
        </div>
    </form>
</div>
@endsection
