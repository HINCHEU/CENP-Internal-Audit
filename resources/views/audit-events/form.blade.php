@extends('layouts.app')

@section('title', 'Schedule Audit - CE&P Internal Audit System')
@section('header', 'Schedule Audit Event')
@section('subheader', 'Create a new audit event and assign auditors.')

@section('content')
<div class="max-w-4xl bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-12">
    <form action="{{ $auditEvent->exists ? route('audit-events.update', $auditEvent->id) : route('audit-events.store') }}" method="POST" class="space-y-8">
        @csrf
        @if($auditEvent->exists)
            @method('PUT')
        @endif

        <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                <i class="ph ph-calendar-plus text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Event Scheduling</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Set the time, project, and assign auditors.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Event Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Audit Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $auditEvent->title) }}" placeholder="e.g. Annual Security Review" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                @error('title')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Project -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Project <span class="text-rose-500">*</span></label>
                <select name="project_id" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    <option value="">Select associated project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $auditEvent->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->project_code }} - {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Schedule -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Audit Date <span class="text-rose-500">*</span></label>
                <input type="date" name="audit_date" value="{{ old('audit_date', $auditEvent->audit_date) }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 text-sm">
                @error('audit_date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Audit Time</label>
                <input type="time" name="audit_time" value="{{ old('audit_time', $auditEvent->audit_time ? \Carbon\Carbon::parse($auditEvent->audit_time)->format('H:i') : null) }}" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700 text-sm">
                @error('audit_time')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Assigned Auditors -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Assign Auditors <span class="text-rose-500">*</span></label>
                <div id="auditor-selector" class="space-y-3">
                    <!-- Hidden inputs for form submission -->
                    <div id="auditor-hidden-inputs"></div>

                    <!-- Search Input -->
                    <div class="relative">
                        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input 
                            v-model="searchQuery" 
                            @input="filterAuditors"
                            type="text" 
                            placeholder="Search auditors by name or department..." 
                            class="w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700"
                        >
                    </div>

                    <!-- Selected Auditors (Tags) -->
                    <div v-if="selectedAuditors.length > 0" class="flex flex-wrap gap-2">
                        <div v-for="auditor in selectedAuditors" :key="auditor.id" class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-50 border border-indigo-200 rounded-lg group hover:bg-indigo-100 transition-colors">
                            <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(auditor.name)}&background=6366F1&color=fff&size=24`" :alt="auditor.name" class="w-5 h-5 rounded-full">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-700">@{{ auditor.name }}</span>
                                <span class="text-[10px] font-medium text-slate-500">@{{ auditor.department }}</span>
                            </div>
                            <button @click="removeAuditor(auditor.id)" type="button" class="ml-1 p-0.5 text-slate-400 hover:text-rose-600 hover:bg-rose-100 rounded transition-colors">
                                <i class="ph ph-x text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Auditor List -->
                    <div class="border border-slate-200 rounded-xl bg-slate-50 overflow-hidden">
                        <div v-if="filteredAuditors.length > 0" class="max-h-96 overflow-y-auto divide-y divide-slate-100">
                            <button 
                                v-for="auditor in filteredAuditors" 
                                :key="auditor.id"
                                @click.prevent="toggleAuditor(auditor)"
                                :class="[
                                    'w-full px-5 py-3.5 flex items-center gap-3 hover:bg-indigo-50 transition-colors text-left group',
                                    isSelected(auditor.id) ? 'bg-indigo-50 border-l-4 border-indigo-500' : ''
                                ]"
                                type="button"
                            >
                                <input 
                                    type="checkbox" 
                                    :checked="isSelected(auditor.id)"
                                    class="w-5 h-5 rounded border-slate-300 accent-indigo-600 cursor-pointer"
                                    @click.stop="toggleAuditor(auditor)"
                                >
                                <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(auditor.name)}&background=CBD5E1&color=1e293b&size=32`" :alt="auditor.name" class="w-8 h-8 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm text-slate-800">@{{ auditor.name }}</p>
                                    <p class="text-xs font-medium text-slate-500">@{{ auditor.department }}</p>
                                </div>
                                <i :class="['ph', isSelected(auditor.id) ? 'ph-check-circle text-indigo-600' : 'ph-circle text-slate-300']" class="text-xl"></i>
                            </button>
                        </div>
                        <div v-else class="px-5 py-8 text-center">
                            <i class="ph ph-magnifying-glass text-slate-300 text-3xl mb-2 block"></i>
                            <p class="text-sm font-medium text-slate-500">No auditors found</p>
                        </div>
                    </div>

                    <!-- Info Text -->
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <span v-if="selectedAuditors.length > 0">@{{ selectedAuditors.length }} auditor<span v-if="selectedAuditors.length !== 1">s</span> selected</span>
                        <span v-else>Select at least one auditor</span>
                    </p>
                </div>
                @error('auditor_ids')<p class="text-rose-500 text-xs mt-2">{{ $message }}</p>@enderror
            </div>

            <script type="module">
                document.addEventListener('DOMContentLoaded', function() {
                    const { createApp, ref, computed } = Vue;

                    const allAuditors = @json($auditors->map(function($a) {
                        return (object)[
                            'id' => (int)$a->id,
                            'name' => $a->name,
                            'department' => $a->department->name ?? 'No Department'
                        ];
                    })->toArray());

                    const selectedAuditorsIds = @json($auditEvent->auditors->pluck('id')->map(fn($id) => (int)$id)->toArray());

                    createApp({
                        setup() {
                            const searchQuery = ref('');
                            const selectedAuditors = ref([]);

                            // Initialize selected auditors
                            if (Array.isArray(allAuditors) && allAuditors.length > 0) {
                                selectedAuditors.value = allAuditors.filter(a => 
                                    Array.isArray(selectedAuditorsIds) && selectedAuditorsIds.includes(parseInt(a.id))
                                );
                            }

                            const isSelected = (auditorId) => {
                                return selectedAuditors.value && selectedAuditors.value.some(a => parseInt(a.id) === parseInt(auditorId));
                            };

                            const filteredAuditors = computed(() => {
                                if (!Array.isArray(allAuditors)) return [];
                                
                                const available = allAuditors.filter(a => !isSelected(a.id));
                                
                                if (!searchQuery.value || !searchQuery.value.trim()) {
                                    return available;
                                }
                                
                                const query = searchQuery.value.toLowerCase();
                                return available.filter(a => 
                                    (a.name && a.name.toLowerCase().includes(query)) || 
                                    (a.department && a.department.toLowerCase().includes(query))
                                );
                            });

                            const toggleAuditor = (auditor) => {
                                if (isSelected(auditor.id)) {
                                    removeAuditor(auditor.id);
                                } else {
                                    addAuditor(auditor);
                                }
                            };

                            const addAuditor = (auditor) => {
                                if (auditor && auditor.id && !isSelected(auditor.id)) {
                                    selectedAuditors.value.push(auditor);
                                    searchQuery.value = '';
                                    updateHiddenInputs();
                                }
                            };

                            const removeAuditor = (auditorId) => {
                                selectedAuditors.value = selectedAuditors.value.filter(a => parseInt(a.id) !== parseInt(auditorId));
                                updateHiddenInputs();
                            };

                            const updateHiddenInputs = () => {
                                const container = document.getElementById('auditor-hidden-inputs');
                                if (!container) return;
                                
                                container.innerHTML = '';
                                if (Array.isArray(selectedAuditors.value)) {
                                    selectedAuditors.value.forEach(auditor => {
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = 'auditor_ids[]';
                                        input.value = auditor.id;
                                        container.appendChild(input);
                                    });
                                }
                            };

                            // Initial setup
                            updateHiddenInputs();

                            return {
                                searchQuery,
                                selectedAuditors,
                                filteredAuditors,
                                isSelected,
                                toggleAuditor,
                                addAuditor,
                                removeAuditor
                            };
                        }
                    }).mount('#auditor-selector');
                });
            </script>
                    {{-- }).mount('#auditor-selector');
                }); --}}
            </script>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description / Objectives</label>
                <textarea name="description" rows="4" placeholder="Describe the objectives and scope of this audit..." class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">{{ old('description', $auditEvent->description) }}</textarea>
                @error('description')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

        </div>

        <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4 mt-10">
            <a href="{{ route('audit-events.index') }}" class="px-6 py-3.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-800 rounded-xl font-bold transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3.5 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2 premium-hover">
                Save Event
            </button>
        </div>
    </form>
</div>
@endsection
