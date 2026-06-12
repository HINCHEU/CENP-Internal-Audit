@extends('layouts.app')

@section('title', 'Score Evaluations - CE&P Internal Audit System')
@section('header', 'Score Evaluations')
@section('subheader', 'Participate in quick evaluations by submitting your score.')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($evaluations as $eval)
    @php
        $isDone = in_array($eval->id, $userScoreEvaluationIds);
    @endphp
    <div class="bg-white rounded-3xl premium-shadow border {{ $isDone ? 'border-emerald-200 ring-1 ring-emerald-500/20' : 'border-slate-100' }} flex flex-col justify-between overflow-hidden group premium-hover">
        <div class="p-6 flex-1 flex flex-col relative">
            <!-- Decorative circle -->
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-indigo-50 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100 mb-5 relative z-10">
                <i class="ph ph-star-half text-2xl"></i>
            </div>
            
            <div class="flex items-start justify-between mb-2 relative z-10 gap-2">
                <h3 class="text-lg font-bold text-slate-800 leading-tight">{{ $eval->title }}</h3>
                @if($isDone)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200 shrink-0">
                        <i class="ph ph-check-circle"></i> Done
                    </span>
                @endif
            </div>
            <p class="text-sm font-medium text-slate-500 line-clamp-3 relative z-10">{{ $eval->description ?: 'No description provided.' }}</p>
        </div>
        
        <div class="p-6 pt-0 border-t border-slate-50 mt-4 relative z-10">
            @if($isDone)
                <a href="{{ route('user-evaluations.show', $eval->id) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white rounded-xl font-bold transition-colors group/btn border border-emerald-200 hover:border-emerald-600">
                    View Score
                    <i class="ph ph-eye text-lg group-hover/btn:translate-x-1 transition-transform"></i>
                </a>
            @else
                <a href="{{ route('user-evaluations.show', $eval->id) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-slate-50 hover:bg-indigo-600 text-slate-700 hover:text-white rounded-xl font-bold transition-colors group/btn">
                    Score Now
                    <i class="ph ph-arrow-right text-lg group-hover/btn:translate-x-1 transition-transform"></i>
                </a>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-12 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ph ph-empty text-4xl text-slate-400"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">No Active Evaluations</h3>
            <p class="text-slate-500 font-medium max-w-md mx-auto">There are currently no active quick evaluations available for scoring. Please check back later.</p>
        </div>
    </div>
    @endforelse
</div>

@if($evaluations->hasPages())
<div class="mt-8 flex justify-center">
    {{ $evaluations->links() }}
</div>
@endif
@endsection
