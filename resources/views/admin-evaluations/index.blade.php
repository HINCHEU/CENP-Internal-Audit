@extends('layouts.app')

@section('title', 'Quick Evaluations - CE&P Internal Audit System')
@section('header', 'Quick Evaluations')
@section('subheader', 'Manage quick evaluations that any auditor can score.')

@section('content')
<div id="module-app" class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
        <div class="relative w-full sm:w-[320px]">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <input v-model="search" type="text" placeholder="Search evaluations..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all focus:bg-white">
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ route('admin-evaluations.analytic-user') }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-5 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 border border-indigo-200">
                <i class="ph ph-users text-xl"></i> Analytic by User
            </a>
            <a href="{{ route('admin-evaluations.analytic-project') }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-5 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 border border-indigo-200">
                <i class="ph ph-briefcase text-xl"></i> Analytic by Project
            </a>
            <a href="{{ route('admin-evaluations.create') }}" class="bg-gradient-primary hover:opacity-90 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/30 premium-hover">
                <i class="ph ph-plus-circle text-xl"></i> Create
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-8 py-5">Title</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-6 py-5">Total Scores</th>
                    <th class="px-6 py-5">Created At</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($evaluations as $e)
                <tr v-show="!search || '{{ strtolower(addslashes($e->title)) }}'.includes(search.toLowerCase())" class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <p class="text-slate-800 font-bold text-sm">{{ $e->title }}</p>
                        @if($e->project)
                            <p class="text-xs font-semibold text-indigo-600 mt-1"><i class="ph ph-briefcase"></i> {{ $e->project->project_code }} - {{ $e->project->name }}</p>
                        @endif
                        @if($e->date)
                            <p class="text-xs text-slate-500 mt-0.5"><i class="ph ph-calendar"></i> {{ $e->date->format('M d, Y') }}</p>
                        @endif
                        <p class="text-[11px] font-medium text-slate-500 mt-0.5 truncate max-w-xs">{{ $e->description }}</p>
                    </td>
                    <td class="px-6 py-5">
                        @if($e->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Active
                            </span>
                        @elseif($e->status === 'completed')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
                                Completed
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-slate-600 font-medium text-sm">{{ $e->scores_count }}</td>
                    <td class="px-6 py-5 text-slate-500 font-medium text-sm">{{ $e->created_at->format('M d, Y') }}</td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin-evaluations.show', $e->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View Analytics">
                                <i class="ph ph-chart-line-up text-lg"></i>
                            </a>
                            <button type="button" class="qr-trigger p-2 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="View QR Code"
                                data-qr-url="{{ url(route('user-evaluations.show', $e->id)) }}"
                                data-event-title="{{ $e->title }}"
                                data-event-id="{{ $e->id }}"
                            >
                                <i class="ph ph-qr-code text-lg"></i>
                            </button>
                            <a href="{{ route('admin-evaluations.edit', $e->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <form id="delete-eval-{{ $e->id }}" action="{{ route('admin-evaluations.destroy', $e->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-eval-{{ $e->id }}', 'this evaluation')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-8 text-center text-slate-500 font-medium">
                        No quick evaluations found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($evaluations->hasPages())
    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
        {{ $evaluations->links() }}
    </div>
    @endif
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

@include('partials.qr-modal')
@endsection
