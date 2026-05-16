@extends('layouts.app')

@section('title', 'Departments - CE&P Internal Audit System')
@section('header', 'Departments')
@section('subheader', 'Manage organizational departments and their statuses.')

@section('content')
<div id="module-app" class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
        <div class="relative w-full sm:w-[320px]">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <input v-model="search" type="text" placeholder="Search departments..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all focus:bg-white">
        </div>
        <a href="{{ route('departments.create') }}" class="w-full sm:w-auto bg-gradient-primary hover:opacity-90 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/30 premium-hover">
            <i class="ph ph-buildings text-xl"></i> Add Department
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-8 py-5">Department Name</th>
                    <th class="px-6 py-5">Description</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($departments as $department)
                <tr v-show="!search || '{{ strtolower(addslashes($department->name . ' ' . $department->description)) }}'.includes(search.toLowerCase())" class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5 font-bold text-slate-800 text-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                            <i class="ph ph-buildings text-lg"></i>
                        </div>
                        {{ $department->name }}
                    </td>
                    <td class="px-6 py-5 text-slate-600 font-medium text-sm">{{ $department->description ?? '-' }}</td>
                    <td class="px-6 py-5">
                        @if($department->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('departments.show', $department->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                            <a href="{{ route('departments.edit', $department->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <form id="delete-dept-{{ $department->id }}" action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-dept-{{ $department->id }}', 'this department')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-8 text-center text-slate-500 font-medium">
                        No departments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const { createApp, ref } = Vue;
        createApp({
            setup() {
                const search = ref('');
                return { search };
            }
        }).mount('#module-app');
    });
</script>
@endsection
