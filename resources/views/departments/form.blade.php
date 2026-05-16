@extends('layouts.app')

@section('title', 'Department Form - CE&P Internal Audit System')
@section('header', 'Department Details')
@section('subheader', 'Create or edit department information.')

@section('content')
<div class="max-w-2xl bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-12">
    <form action="{{ $department->exists ? route('departments.update', $department->id) : route('departments.store') }}" method="POST" class="space-y-8">
        @csrf
        @if($department->exists)
            @method('PUT')
        @endif
        
        <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                <i class="ph ph-buildings text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Department Configuration</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Define department name and its role.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Department Name <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $department->name) }}" placeholder="e.g. IT Infrastructure" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
            @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
            <textarea name="description" rows="4" placeholder="Briefly describe the department's role..." class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">{{ old('description', $department->description) }}</textarea>
            @error('description')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
            <select name="status" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                <option value="active" {{ old('status', $department->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $department->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4 mt-10">
            <a href="{{ route('departments.index') }}" class="px-6 py-3.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-800 rounded-xl font-bold transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3.5 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2 premium-hover">
                Save Department
            </button>
        </div>
    </form>
</div>
@endsection
