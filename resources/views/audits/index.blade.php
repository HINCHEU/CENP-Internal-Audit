@extends('layouts.app')

@section('title', 'My Audits - CE&P Internal Audit System')
@section('header', 'My Assigned Audits')
@section('subheader', 'View and submit your assigned audit tasks.')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

    @forelse($events as $event)
        @php
            $eventDate = \Carbon\Carbon::parse($event->audit_date);
            $isToday = $eventDate->isToday();
            $isPast = $eventDate->isPast() && !$isToday;
            $finding = $event->findings->first();
            $isCompleted = !is_null($finding);
        @endphp
        
        <div class="bg-white rounded-3xl premium-shadow {{ $isToday && !$isCompleted ? 'border-indigo-200 ring-1 ring-indigo-500/10' : 'border-slate-100' }} overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col group relative">
            @if($isCompleted)
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            @elseif($isToday)
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-primary z-20"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-transparent to-slate-50/50 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            @endif

            <div class="p-8 pb-6 border-b {{ $isToday && !$isCompleted ? 'border-indigo-100/50' : 'border-slate-100' }} bg-white relative z-10">
                <div class="flex justify-between items-start mb-6">
                    @if($isCompleted)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200">
                            <i class="ph ph-check-circle"></i> Done
                        </span>
                    @elseif($isPast)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-200">Past</span>
                    @elseif($isToday)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-indigo-50 text-indigo-600 border border-indigo-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span> Today
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                    @endif
                    <span class="text-xs font-bold {{ $isToday && !$isCompleted ? 'text-rose-600 bg-rose-50 border-rose-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100' }} px-3 py-1.5 rounded-lg border shadow-sm">{{ $eventDate->format('M d, Y') }}</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-2 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $event->title }}</h3>
                <p class="text-sm text-slate-500 font-medium flex items-center gap-2">
                    <i class="ph ph-briefcase text-slate-400 text-lg"></i> {{ $event->project->name ?? 'No Project' }}
                </p>
            </div>

            <div class="p-8 flex-1 flex flex-col gap-4 relative z-10">
                @if($event->description)
                    <div class="{{ $isToday ? 'bg-indigo-50/50 border border-indigo-100/50' : 'bg-slate-50 border border-slate-200' }} rounded-xl p-4">
                        <p class="text-sm font-medium text-slate-600 leading-relaxed line-clamp-3">{{ $event->description }}</p>
                    </div>
                @endif
                
                @if($event->audit_time)
                <div class="mt-2 flex items-center gap-4 text-sm text-slate-600">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-200 shrink-0">
                        <i class="ph ph-clock text-slate-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($event->audit_time)->format('h:i A') }}</p>
                        <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider mt-0.5">Scheduled Time</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="p-6 pt-0 relative z-10 mt-auto">
                @if(!$isCompleted)
                    <a href="{{ route('audits.submit', $event->id) }}" class="w-full flex items-center justify-center gap-2 {{ $isToday ? 'bg-gradient-primary hover:opacity-90 text-white shadow-indigo-500/30 group-hover:shadow-indigo-500/40' : 'bg-slate-800 hover:bg-slate-900 text-white shadow-slate-900/20' }} font-bold py-3.5 rounded-xl transition-all shadow-lg group-hover:shadow-xl">
                        {{ $isPast ? 'Submit Late Audit' : 'Start Audit' }} <i class="ph ph-arrow-right font-bold"></i>
                    </a>
                @else
                    @if($finding->edit_request_status === 'approved')
                        <a href="{{ route('audits.submit', $event->id) }}" class="w-full flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-amber-500/30 group-hover:shadow-amber-500/40">
                            Resubmit Audit <i class="ph ph-arrow-counter-clockwise font-bold"></i>
                        </a>
                    @elseif($finding->edit_request_status === 'pending')
                        <button disabled class="w-full flex items-center justify-center gap-2 bg-slate-200 text-slate-500 font-bold py-3.5 rounded-xl transition-all cursor-not-allowed">
                            Edit Requested (Pending) <i class="ph ph-hourglass-high font-bold"></i>
                        </button>
                    @else
                        <form action="{{ route('audits.request-edit', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white border-2 border-slate-200 hover:border-indigo-500 hover:text-indigo-600 text-slate-600 font-bold py-3 rounded-xl transition-all shadow-sm">
                                Request Edit <i class="ph ph-pencil-simple font-bold"></i>
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-100">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="ph ph-calendar-check text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">No Audits Assigned</h3>
            <p class="text-sm text-slate-500 mt-1">You have no pending audits at this time.</p>
        </div>
    @endforelse

</div>
@endsection
