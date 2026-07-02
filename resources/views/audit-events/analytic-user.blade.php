@extends('layouts.app')

@section('title', 'Analytic by User - Audit Events')
@section('header', 'Analytic by User')
@section('subheader', 'View audit finding scores grouped by assigned auditor across audit events.')

@section('content')
<div class="mb-6 flex justify-end">
    <a href="{{ route('audit-events.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-medium premium-shadow flex items-center gap-2">
        <i class="ph ph-arrow-left text-lg"></i> Back to Audit Events
    </a>
</div>

<div class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead>
                <tr>
                    <th rowspan="2" class="px-6 py-4 bg-slate-50 border-b border-r border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold sticky left-0 z-10">No.</th>
                    <th rowspan="2" class="px-6 py-4 bg-slate-50 border-b border-r border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold sticky left-[60px] z-10">Name</th>
                    <th rowspan="2" class="px-4 py-4 bg-slate-50 border-b border-r border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold text-center">Gender</th>
                    <th rowspan="2" class="px-6 py-4 bg-slate-50 border-b border-r border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold">Role</th>
                    <th rowspan="2" class="px-6 py-4 bg-slate-50 border-b border-r border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold">Department</th>

                    @foreach($events as $event)
                        <th colspan="2" class="px-6 py-3 bg-indigo-50 border-b border-r border-indigo-100 text-indigo-700 text-[11px] uppercase tracking-wider font-extrabold text-center">
                            {{ $event->title }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($events as $event)
                        <th class="px-6 py-3 bg-indigo-50/50 border-b border-r border-indigo-100 text-indigo-600 text-[11px] uppercase tracking-wider font-bold text-center">Score</th>
                        <th class="px-6 py-3 bg-indigo-50/50 border-b border-r border-indigo-100 text-indigo-600 text-[11px] uppercase tracking-wider font-bold text-center">Finding</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $index => $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 bg-white border-r border-slate-100 text-slate-600 font-medium text-sm sticky left-0 z-10">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 bg-white border-r border-slate-100 sticky left-[60px] z-10">
                            <p class="text-slate-800 font-bold text-sm whitespace-nowrap">{{ $user->name }}</p>
                        </td>
                        <td class="px-4 py-4 border-r border-slate-100 text-center text-slate-600 font-medium text-sm">{{ $user->gender ?? '-' }}</td>
                        <td class="px-6 py-4 border-r border-slate-100 text-slate-600 font-medium text-sm whitespace-nowrap">{{ $user->role === 'admin' ? 'Administrator' : 'User' }}</td>
                        <td class="px-6 py-4 border-r border-slate-100 text-slate-600 font-medium text-sm whitespace-nowrap">{{ $user->department->name ?? '-' }}</td>

                        @foreach($events as $event)
                            @php
                                $finding = $user->submittedFindings->where('audit_event_id', $event->id)->first();
                                $score = $finding?->parsedScore();
                            @endphp
                            <td class="px-6 py-4 border-r border-slate-100 text-center font-bold {{ $score !== null ? 'text-indigo-600' : 'text-slate-300' }}">
                                {{ $score !== null ? $score : '-' }}
                            </td>
                            <td class="px-6 py-4 border-r border-slate-100 text-sm text-slate-600 max-w-[220px] truncate" title="{{ $finding ? $finding->finding_type : '' }}">
                                {{ $finding ? $finding->finding_type : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 5 + ($events->count() * 2) }}" class="px-8 py-8 text-center text-slate-500 font-medium">
                            No assigned auditors found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
