@extends('layouts.app')

@section('title', 'Projects - CE&P Internal Audit System')
@section('header', 'Projects')
@section('subheader', 'Manage and track all organizational projects.')

@section('content')
<div id="module-app" class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
        <div class="relative w-full sm:w-[320px]">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <input v-model="search" type="text" placeholder="Search projects..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all focus:bg-white">
        </div>
        <a href="{{ route('projects.create') }}" class="w-full sm:w-auto bg-gradient-primary hover:opacity-90 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/30 premium-hover">
            <i class="ph ph-plus-circle text-xl"></i> New Project
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-8 py-5">Project Details</th>
                    <th class="px-6 py-5">Department</th>
                    <th class="px-6 py-5">Manager</th>
                    <th class="px-6 py-5">Timeline</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($projects as $proj)
                <tr v-show="!search || '{{ strtolower(addslashes($proj->name . ' ' . $proj->project_code . ' ' . ($proj->department->name ?? '') . ' ' . ($proj->manager->name ?? ''))) }}'.includes(search.toLowerCase())" class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0 group-hover:scale-105 transition-transform">
                                <i class="ph ph-folder text-xl"></i>
                            </div>
                            <div>
                                <p class="text-slate-800 font-bold text-sm">{{ $proj->name }}</p>
                                <p class="text-[11px] font-semibold text-slate-500 mt-0.5">{{ $proj->project_code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-slate-600 font-medium text-sm">{{ $proj->department->name ?? '-' }}</td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            @if($proj->manager)
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($proj->manager->name) }}&background=E2E8F0&color=475569" class="w-6 h-6 rounded-full" alt="PM">
                                <span class="text-slate-700 font-medium text-sm">{{ $proj->manager->name }}</span>
                            @else
                                <span class="text-slate-500 font-medium text-sm">-</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-slate-600 font-medium text-sm">
                            {{ $proj->start_date ? \Carbon\Carbon::parse($proj->start_date)->format('M d, Y') : 'N/A' }} - 
                            {{ $proj->end_date ? \Carbon\Carbon::parse($proj->end_date)->format('M d, Y') : 'N/A' }}
                        </p>
                    </td>
                    <td class="px-6 py-5">
                        @if($proj->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        @elseif($proj->status === 'on_hold')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> On Hold
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Completed
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('projects.edit', $proj->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <form id="delete-proj-{{ $proj->id }}" action="{{ route('projects.destroy', $proj->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-proj-{{ $proj->id }}', 'this project')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-8 text-center text-slate-500 font-medium">
                        No projects found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
        {{ $projects->links() }}
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
