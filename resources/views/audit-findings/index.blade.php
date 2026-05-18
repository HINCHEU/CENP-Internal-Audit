@extends('layouts.app')

@section('title', 'All Findings - CE&P Internal Audit System')
@section('header', 'Audit Findings')
@section('subheader', 'Complete log of all recorded audit findings.')

@section('content')
<div class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden flex flex-col">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="ph ph-arrow-left text-lg"></i> Back to Reports
        </a>
        <p class="text-sm font-semibold text-slate-500">{{ $findings->total() }} finding{{ $findings->total() === 1 ? '' : 's' }}</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[10px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-6 py-4">Finding Details</th>
                    <th class="px-6 py-4">Audit Event</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Edit Request</th>
                    <th class="px-6 py-4 text-right">Date Logged</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($findings as $finding)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-slate-800 font-bold text-sm line-clamp-1 max-w-[300px]">{{ explode("\n", $finding->description)[0] }}</p>
                        <p class="text-[11px] font-semibold text-slate-500 mt-0.5">By {{ $finding->auditor->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium text-sm">
                        <span class="block truncate max-w-[200px]">{{ $finding->auditEvent->title }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @include('audit-findings.partials.finding-type-badge', ['finding' => $finding, 'compact' => true])
                    </td>
                    <td class="px-6 py-4">
                        @if($finding->status == 'open')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-rose-600 bg-rose-50">Open</span>
                        @elseif($finding->status == 'resolved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-amber-600 bg-amber-50">Resolved</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-emerald-600 bg-emerald-50">Closed</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($finding->edit_request_status === 'pending')
                            <div class="flex items-center gap-2">
                                <form action="{{ route('audit-findings.approve-edit', $finding->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition-colors">Approve</button>
                                </form>
                                <form action="{{ route('audit-findings.approve-edit', $finding->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-lg transition-colors">Reject</button>
                                </form>
                            </div>
                        @elseif($finding->edit_request_status === 'approved')
                            <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-md">Approved</span>
                        @elseif($finding->edit_request_status === 'rejected')
                            <span class="text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-md">Rejected</span>
                        @else
                            <span class="text-xs font-medium text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium text-slate-500">
                        {{ $finding->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('audit-findings.show', $finding->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors inline-block" title="View Details">
                            <i class="ph ph-eye text-lg"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">No findings recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($findings->hasPages())
    <div class="p-6 border-t border-slate-100">
        {{ $findings->links() }}
    </div>
    @endif
</div>
@endsection
