@extends('layouts.app')

@section('title', 'Submit Audit - CE&P Internal Audit System')
@section('header', 'Audit Submission')
@section('subheader', $auditEvent->title . ' - ' . ($auditEvent->project->name ?? 'No Project'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <form action="{{ route('audit-findings.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-10 space-y-10">
            @csrf
            <input type="hidden" name="audit_event_id" value="{{ $auditEvent->id }}">
            
            <!-- Overall Score & Finding Type -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 border-b border-slate-100 pb-8 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Evaluation Score</h3>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Overall Score (0-100) <span class="text-rose-500">*</span></label>
                    <input type="number" name="score" value="{{ old('score') }}" min="0" max="100" class="w-full px-5 py-4 text-2xl border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-extrabold text-indigo-600 bg-slate-50 transition-all text-center sm:text-left" placeholder="e.g. 85">
                    @error('score')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Finding Classification</h3>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Finding Type <span class="text-rose-500">*</span></label>
                    <select name="finding_type" class="w-full px-5 py-4 text-base border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-bold text-slate-700 bg-slate-50 transition-all cursor-pointer">
                        <option value="">Select a type</option>
                        <option value="Observation" {{ old('finding_type') == 'Observation' ? 'selected' : '' }}>Observation</option>
                        <option value="Minor Non-conformance" {{ old('finding_type') == 'Minor Non-conformance' ? 'selected' : '' }}>Minor Non-conformance</option>
                        <option value="Major Non-conformance" {{ old('finding_type') == 'Major Non-conformance' ? 'selected' : '' }}>Major Non-conformance</option>
                    </select>
                    @error('finding_type')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Findings & Comments -->
            <div>
                <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6">Findings & Comments</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Detailed Description <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="5" class="w-full px-5 py-4 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700" placeholder="Provide a detailed summary of the audit findings, executive summary, and key issues...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Evidence / Attachments -->
            <div>
                <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6">Evidence & Attachments</h3>
                
                <div class="relative border-2 border-dashed border-indigo-200 rounded-2xl p-12 text-center bg-indigo-50/30 hover:bg-indigo-50/80 transition-colors cursor-pointer group">
                    <input type="file" name="evidence_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.jpg,.png,.docx">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:scale-110 group-hover:shadow-md transition-all duration-300 border border-indigo-100 relative z-0">
                        <i class="ph ph-cloud-arrow-up text-3xl text-indigo-500"></i>
                    </div>
                    <p class="text-base font-bold text-slate-700 mb-1 relative z-0">Click to upload or drag and drop</p>
                    <p class="text-sm font-medium text-slate-500 relative z-0">PDF, JPG, PNG or DOCX (max. 10MB)</p>
                </div>
                @error('evidence_file')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Actions -->
            <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="{{ route('audits.index') }}" class="px-6 py-3.5 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-800 rounded-xl font-bold transition-all shadow-sm premium-hover">Cancel</a>
                <button type="submit" class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold transition-all shadow-lg shadow-emerald-500/30 flex items-center gap-2 premium-hover">
                    <i class="ph ph-check-circle text-xl"></i> Final Submit
                </button>
            </div>
        </form>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-8">
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full blur-3xl"></div>
            <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-6 relative z-10">Event Information</h3>
            <ul class="space-y-6 relative z-10">
                <li class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="ph ph-briefcase text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Project</p>
                        <p class="text-sm font-bold text-slate-800 leading-tight">{{ $auditEvent->project->name ?? 'None' }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        <i class="ph ph-calendar text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Date & Time</p>
                        <p class="text-sm font-bold text-slate-800 leading-tight">
                            {{ \Carbon\Carbon::parse($auditEvent->audit_date)->format('M d, Y') }}<br>
                            @if($auditEvent->audit_time) {{ \Carbon\Carbon::parse($auditEvent->audit_time)->format('h:i A') }} @else TBD @endif
                        </p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="ph ph-map-pin text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Location</p>
                        <p class="text-sm font-bold text-slate-800 leading-tight">Remote / TBD</p>
                    </div>
                </li>
                <li class="pt-6 border-t border-slate-100">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Assigned Auditors</p>
                    <div class="flex flex-col gap-3">
                        @foreach($auditEvent->auditors as $auditor)
                        <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <img class="h-8 w-8 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode($auditor->name) }}&background=6366F1&color=fff" alt=""/>
                            <span class="text-sm font-bold text-slate-700">{{ $auditor->name }}</span>
                        </div>
                        @endforeach
                        @if($auditEvent->auditors->isEmpty())
                        <div class="text-sm text-slate-500 font-medium">No auditors assigned.</div>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
        
        <div class="bg-indigo-50/80 rounded-3xl border border-indigo-100 p-8">
            <div class="flex flex-col gap-3">
                <div class="w-10 h-10 rounded-xl bg-white text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="ph ph-info text-xl font-bold"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-900 mb-2 mt-2">Submission Guide</h4>
                    <p class="text-xs font-medium text-indigo-700/80 leading-relaxed">Please ensure all required fields are filled out. Attachments are mandatory if the score falls below 70. Once final submission is done, it cannot be edited without admin approval.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
