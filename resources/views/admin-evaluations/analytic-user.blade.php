@extends('layouts.app')

@section('title', 'Analytic by User - Quick Evaluations')
@section('header', 'Analytic by User')
@section('subheader', 'View evaluation scores and comments grouped by user across all quick evaluations.')

@section('content')
<div class="mb-6 flex justify-end gap-3">
    <a href="{{ route('admin-evaluations.analytic-user.export') }}" class="px-5 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl hover:bg-emerald-100 transition-colors font-bold premium-shadow flex items-center gap-2">
        <i class="ph ph-file-xls text-xl"></i> Export to Excel
    </a>
    <a href="{{ route('admin-evaluations.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-medium premium-shadow flex items-center gap-2">
        <i class="ph ph-arrow-left text-lg"></i> Back to Evaluations
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
                    
                    @foreach($evaluations as $evaluation)
                        <th colspan="2" class="px-6 py-3 bg-indigo-50 border-b border-r border-indigo-100 text-indigo-700 text-[11px] uppercase tracking-wider font-extrabold text-center">
                            {{ $evaluation->title }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($evaluations as $evaluation)
                        <th class="px-6 py-3 bg-indigo-50/50 border-b border-r border-indigo-100 text-indigo-600 text-[11px] uppercase tracking-wider font-bold text-center">Score</th>
                        <th class="px-6 py-3 bg-indigo-50/50 border-b border-r border-indigo-100 text-indigo-600 text-[11px] uppercase tracking-wider font-bold text-center">Comment</th>
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
                        
                        @foreach($evaluations as $evaluation)
                            @php
                                $score = $user->evaluationScores->where('evaluation_id', $evaluation->id)->first();
                            @endphp
                            <td class="px-6 py-4 border-r border-slate-100 text-center font-bold {{ $score ? 'text-indigo-600' : 'text-slate-300' }}">
                                {{ $score ? $score->score : '-' }}
                            </td>
                            <td class="px-6 py-4 border-r border-slate-100 text-sm text-slate-600 max-w-[200px] truncate" title="{{ $score ? $score->comment : '' }}">
                                {{ $score && $score->comment ? $score->comment : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 5 + ($evaluations->count() * 2) }}" class="px-8 py-8 text-center text-slate-500 font-medium">
                            No evaluators found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
