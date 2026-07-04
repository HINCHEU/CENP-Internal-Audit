@extends('layouts.app')

@section('title', 'Thank You - CE&P Internal Audit System')
@section('header', 'Thank You')
@section('subheader', 'Your evaluation has been successfully submitted.')

@section('content')
<div class="max-w-2xl mx-auto mt-12">
    <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-16 text-center relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-50 rounded-full opacity-50 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-emerald-50 rounded-full opacity-50 blur-2xl"></div>

        <div class="relative z-10">
            <div class="w-24 h-24 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center border border-emerald-100 mx-auto mb-8 shadow-sm">
                <i class="ph ph-check-circle text-5xl"></i>
            </div>
            
            <h2 class="text-3xl font-black text-slate-800 mb-4">Thank You!</h2>
            <p class="text-lg text-slate-500 font-medium mb-10 leading-relaxed">
                Your evaluation for <strong class="text-slate-700">{{ $evaluation->title }}</strong> has been submitted successfully. We appreciate your valuable feedback!
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user-evaluations.index') }}" class="px-8 py-4 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2 premium-hover w-full sm:w-auto">
                    <i class="ph ph-list-bullets text-xl"></i> Back to Evaluations
                </a>
                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="px-8 py-4 text-slate-700 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl font-bold transition-all shadow-sm flex items-center justify-center gap-2 premium-hover w-full sm:w-auto">
                    <i class="ph ph-squares-four text-xl"></i> Go to Dashboard
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
