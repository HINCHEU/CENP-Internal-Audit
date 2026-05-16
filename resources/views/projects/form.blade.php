@extends('layouts.app')

@section('title', 'Project Form - CE&P Internal Audit System')
@section('header', 'Project Details')
@section('subheader', 'Create or edit project information.')

@section('content')
<div class="max-w-4xl bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-12">
    
    <form action="{{ $project->exists ? route('projects.update', $project->id) : route('projects.store') }}" method="POST" class="space-y-8">
        @csrf
        @if($project->exists)
            @method('PUT')
        @endif

        <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                <i class="ph ph-briefcase text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Project Configuration</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Setup the core details for this project.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Project Code -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Project Code</label>
                <input type="text" name="project_code" value="{{ old('project_code', $project->project_code) }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 text-slate-500 font-bold" readonly>
                @error('project_code')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Project Name -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Project Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $project->name) }}" placeholder="e.g. Server Migration" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Department -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Department <span class="text-rose-500">*</span></label>
                <select name="department_id" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    <option value="">Select a department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $project->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Project Manager -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Project Manager (PM) <span class="text-rose-500">*</span></label>
                <select name="project_manager_id" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    <option value="">Select a user</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('project_manager_id', $project->project_manager_id) == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                    @endforeach
                </select>
                @error('project_manager_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Location -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Location</label>
                <input type="text" name="location" value="{{ old('location', $project->location) }}" placeholder="e.g. Headquarters, New York" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                @error('location')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Timeline -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', $project->start_date) }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 text-sm">
                @error('start_date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date', $project->end_date) }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 text-sm">
                @error('end_date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                <select name="status" class="w-full md:w-1/2 px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                </select>
                @error('status')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4 mt-10">
            <a href="{{ route('projects.index') }}" class="px-6 py-3.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-800 rounded-xl font-bold transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3.5 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2 premium-hover">
                Save Project
            </button>
        </div>
    </form>
</div>
@endsection
