@php
    $compact = $compact ?? false;
@endphp
@if($finding->finding_type === \App\Models\AuditFinding::TYPE_COMMENDATION)
    <span class="inline-flex items-center gap-1.5 {{ $compact ? 'px-3 py-1 rounded-full text-[10px]' : 'px-3 py-1.5 rounded-lg text-sm' }} font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200">Commendation</span>
@elseif($finding->finding_type === \App\Models\AuditFinding::TYPE_NON_CONFORMANCE)
    <span class="inline-flex items-center gap-1.5 {{ $compact ? 'px-3 py-1 rounded-full text-[10px]' : 'px-3 py-1.5 rounded-lg text-sm' }} font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">{{ $compact ? 'NCR' : 'Non-conformance Report' }}</span>
@elseif($finding->finding_type === \App\Models\AuditFinding::TYPE_OBSERVATION)
    <span class="inline-flex items-center gap-1.5 {{ $compact ? 'px-3 py-1 rounded-full text-[10px]' : 'px-3 py-1.5 rounded-lg text-sm' }} font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-200">{{ $compact ? 'OBS' : 'Observation' }}</span>
@else
    <span class="inline-flex items-center gap-1.5 {{ $compact ? 'px-3 py-1 rounded-full text-[10px]' : 'px-3 py-1.5 rounded-lg text-sm' }} font-bold bg-slate-50 text-slate-600 border border-slate-200">{{ $finding->finding_type }}</span>
@endif
