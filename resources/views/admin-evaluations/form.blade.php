@extends('layouts.app')

@section('title', 'Evaluation Form - CE&P Internal Audit System')
@section('header', 'Evaluation Details')
@section('subheader', 'Create or edit a quick evaluation.')

@section('content')
<div class="max-w-4xl bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-12">
    <form action="{{ $evaluation->exists ? route('admin-evaluations.update', $evaluation->id) : route('admin-evaluations.store') }}" method="POST" class="space-y-10">
        @csrf
        @if($evaluation->exists)
            @method('PUT')
        @endif
        
        <div>
            <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                    <i class="ph ph-star text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Evaluation Information</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">Configure the quick evaluation details.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Project <span class="text-rose-500">*</span></label>
                    <select name="project_id" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $evaluation->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->project_code }} - {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', $evaluation->date ? $evaluation->date->format('Y-m-d') : '') }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700" required>
                    @error('date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $evaluation->title) }}" placeholder="e.g. Q3 Internal Assessment" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    @error('title')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                    <textarea name="description" rows="4" placeholder="Brief details about what to evaluate..." class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">{{ old('description', $evaluation->description) }}</textarea>
                    @error('description')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:w-1/2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                        <option value="active" {{ old('status', $evaluation->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $evaluation->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="inactive" {{ old('status', $evaluation->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4 mt-10">
            <a href="{{ route('admin-evaluations.index') }}" class="px-6 py-3.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-800 rounded-xl font-bold transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3.5 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2 premium-hover">
                Save Evaluation
            </button>
        </div>
    </form>
</div>
@endsection
