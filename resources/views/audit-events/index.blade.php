@extends('layouts.app')

@section('title', 'Audit Events - CE&P Internal Audit System')
@section('header', 'Audit Events')
@section('subheader', 'Manage and schedule audit events across projects.')

@section('content')
<div id="module-app" class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <div class="relative w-full sm:w-[320px]">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input v-model="search" type="text" placeholder="Search events..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all focus:bg-white">
            </div>
            <select class="px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-600 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                <option>All Statuses</option>
                <option>Pending</option>
                <option>In Progress</option>
                <option>Completed</option>
            </select>
        </div>
        <a href="{{ route('audit-events.create') }}" class="w-full sm:w-auto bg-gradient-primary hover:opacity-90 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/30 premium-hover">
            <i class="ph ph-calendar-plus text-xl"></i> Schedule Audit
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-8 py-5">Audit Details</th>
                    <th class="px-6 py-5">Project</th>
                    <th class="px-6 py-5">Schedule</th>
                    <th class="px-6 py-5">Auditors</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($events as $event)
                <tr v-show="!search || '{{ strtolower(addslashes($event->title . ' ' . ($event->project->name ?? ''))) }}'.includes(search.toLowerCase())" class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <p class="text-slate-800 font-bold text-sm">{{ $event->title }}</p>
                        <p class="text-[11px] font-semibold text-slate-500 mt-0.5">EVT-{{ $event->id }}</p>
                    </td>
                    <td class="px-6 py-5 text-slate-600 font-medium text-sm">{{ $event->project->name ?? '-' }}</td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold text-sm">
                            <i class="ph ph-calendar-blank text-indigo-500"></i> {{ \Carbon\Carbon::parse($event->audit_date)->format('M d, Y') }}
                        </div>
                        @if($event->audit_time)
                        <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500 mt-1">
                            <i class="ph ph-clock text-slate-400"></i> {{ \Carbon\Carbon::parse($event->audit_time)->format('h:i A') }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex -space-x-3 overflow-hidden">
                            @foreach($event->auditors as $auditor)
                                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode($auditor->name) }}&background=6366F1&color=fff" alt="{{ $auditor->name }}" title="{{ $auditor->name }}"/>
                            @endforeach
                            @if($event->auditors->isEmpty())
                                <span class="text-slate-400 text-xs font-medium">None</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @php
                            $eventDate = \Carbon\Carbon::parse($event->audit_date);
                            $now = \Carbon\Carbon::now();
                        @endphp
                        
                        @if($eventDate->isFuture())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                Pending
                            </span>
                        @elseif($eventDate->isToday())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span> Today
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Past
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('audit-events.show', $event->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                            <a href="{{ route('audit-events.edit', $event->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <form id="delete-event-{{ $event->id }}" action="{{ route('audit-events.destroy', $event->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-event-{{ $event->id }}', 'this audit event')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-8 text-center text-slate-500 font-medium">
                        No audit events scheduled yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
        {{ $events->links() }}
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
