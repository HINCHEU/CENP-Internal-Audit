@extends('layouts.app')

@section('title', 'Analytic by Project - Quick Evaluations')
@section('header', 'Analytic by Project')
@section('subheader', 'View evaluation scores aggregated by project and dates.')

@section('content')
<div class="mb-6 flex justify-end">
    <a href="{{ route('admin-evaluations.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-medium premium-shadow flex items-center gap-2">
        <i class="ph ph-arrow-left text-lg"></i> Back to Evaluations
    </a>
</div>

<div class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 border-r border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold sticky left-0 z-10 bg-slate-50 w-16 text-center">No.</th>
                    <th class="px-6 py-4 border-r border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold sticky left-[64px] z-10 bg-slate-50 min-w-[250px]">Project</th>
                    
                    @foreach($dates as $date)
                        <th class="px-4 py-4 border-r border-slate-200 text-slate-700 text-[11px] uppercase tracking-wider font-extrabold text-center min-w-[100px] bg-slate-50/80">
                            {{ $date->format('d/m/Y') }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($projects as $index => $project)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 bg-white border-r border-slate-100 text-slate-600 font-medium text-sm sticky left-0 z-10 text-center">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 bg-white border-r border-slate-100 sticky left-[64px] z-10">
                            <p class="text-slate-800 font-bold text-sm">{{ $project->name }}</p>
                            @if($project->project_code)
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $project->project_code }}</p>
                            @endif
                        </td>
                        
                        @foreach($dates as $date)
                            @php
                                $dateKey = $date->format('Y-m-d');
                                $score = $projectScores[$project->id][$dateKey] ?? null;
                            @endphp
                            <td class="px-4 py-4 border-r border-slate-100 text-center font-bold">
                                @if($score !== null)
                                    <span class="{{ $score >= 90 ? 'text-emerald-600' : ($score >= 80 ? 'text-sky-600' : 'text-rose-600') }}">
                                        {{ number_format($score, 2) }}%
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 2 + $dates->count() }}" class="px-8 py-8 text-center text-slate-500 font-medium">
                            No projects found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
