@extends('layouts.app')

@section('title', 'View Department - CE&P Internal Audit System')
@section('header', 'Department Details')
@section('subheader', 'Detailed information about the department.')

@section('content')
<div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
    <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-6">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
            <i class="ph ph-buildings text-3xl"></i>
        </div>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800">{{ $department->name }}</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Department Information</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Description</h3>
            <p class="text-base font-semibold text-slate-800">{{ $department->description ?? 'No description provided.' }}</p>
        </div>
        
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status</h3>
            <div>
                @if($department->status === 'active')
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-rose-50 text-rose-600 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Inactive
                    </span>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Created At</h3>
            <p class="text-base font-semibold text-slate-800">{{ $department->created_at->format('M d, Y h:i A') }}</p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Last Updated</h3>
            <p class="text-base font-semibold text-slate-800">{{ $department->updated_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <div class="pt-8 border-t border-slate-100 mt-8 flex items-center justify-start gap-4">
        <a href="{{ route('departments.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Back to List</a>
        <a href="{{ route('departments.edit', $department->id) }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-colors shadow-sm">Edit Department</a>
    </div>
</div>
@endsection
