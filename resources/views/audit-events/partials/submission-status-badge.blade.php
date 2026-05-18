@php
    $status = $status ?? 'pending';
@endphp

@if($status === 'completed')
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completed
    </span>
@elseif($status === 'in_progress')
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span> In Progress
    </span>
@else
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
        Pending
    </span>
@endif
