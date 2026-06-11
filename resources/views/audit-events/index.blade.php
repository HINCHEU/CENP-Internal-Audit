@extends('layouts.app')

@section('title', 'Audit Events - CE&P Internal Audit System')
@section('header', 'Audit Events')
@section('subheader', 'Manage and schedule audit events across projects.')

@section('content')
<div id="module-app" class="bg-white rounded-3xl premium-shadow border border-slate-100 flex flex-col">
    <!-- Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <div class="relative w-full sm:w-[320px]">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input v-model="search" type="text" placeholder="Search events..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all focus:bg-white">
            </div>
            <select v-model="statusFilter" class="px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-600 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <a href="{{ route('audit-events.create') }}" class="w-full sm:w-auto bg-gradient-primary hover:opacity-90 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/30 premium-hover">
            <i class="ph ph-calendar-plus text-xl"></i> Schedule Audit
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto overflow-y-visible">
        <table class="w-full text-left border-collapse overflow-visible">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-8 py-5">Audit Details</th>
                    <th class="px-6 py-5">Project</th>
                    <th class="px-6 py-5">Schedule</th>
                    <th class="px-6 py-5">Auditors</th>
                    <th class="px-6 py-5">Submitted</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($events as $event)
                @php
                    $eventStatus = strtolower(str_replace(' ', '_', $event->submissionStatus()));
                @endphp
                <tr v-show="filterRow('{{ strtolower(addslashes($event->title . ' ' . ($event->project->name ?? ''))) }}', '{{ $eventStatus }}')" :data-event-id="'{{ $event->id }}'" :data-status="'{{ $eventStatus }}'" class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <p class="text-slate-800 font-bold text-sm">{{ $event->title }}</p>
                        <p class="text-[11px] font-semibold text-slate-500 mt-0.5">EVT-{{ $event->id }}</p>
                    </td>
                    <td class="px-6 py-5 text-slate-600 font-medium text-sm">{{ $event->project->name ?? '-' }}</td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold text-sm">
                            <i class="ph ph-calendar-blank text-indigo-500"></i> {{ \Carbon\Carbon::parse($event->audit_date)->format('M d, Y') }}
                        </div>
                        @if($event->audit_time)
                        <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500 mt-1">
                            <i class="ph ph-clock text-slate-400"></i> {{ \Carbon\Carbon::parse($event->audit_time)->format('h:i A') }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex -space-x-3 overflow-hidden">
                            @foreach($event->auditors as $auditor)
                                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode($auditor->name) }}&background=6366F1&color=fff" alt="{{ $auditor->name }}" title="{{ $auditor->name }}"/>
                            @endforeach
                            @if($event->auditors->isEmpty())
                                <span class="text-slate-400 text-xs font-medium">None</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @php
                            $totalAuditors = $event->auditors->count();
                            $submittedUserIds = $event->findings->pluck('user_id');
                            $submittedCount = $event->auditors->whereIn('id', $submittedUserIds)->count();
                            $percent = $totalAuditors > 0 ? (int) round(($submittedCount / $totalAuditors) * 100) : 0;
                            $submittedAuditors = $event->auditors->whereIn('id', $submittedUserIds);
                            $pendingAuditors = $event->auditors->whereNotIn('id', $submittedUserIds);
                        @endphp

                        @if($totalAuditors === 0)
                            <span class="text-slate-400 text-xs font-medium">-</span>
                        @else
                            <button
                                type="button"
                                class="submission-trigger text-left min-w-[120px] rounded-xl px-3 py-2 -mx-3 -my-2 cursor-pointer hover:bg-indigo-50/80 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                data-submitted='@json($submittedAuditors->pluck("name")->values())'
                                data-pending='@json($pendingAuditors->pluck("name")->values())'
                                data-percent="{{ $percent }}"
                                aria-expanded="false"
                                aria-haspopup="true"
                            >
                                <p class="text-sm font-bold text-slate-800 whitespace-nowrap flex items-center gap-1.5">
                                    {{ $submittedCount }} of {{ $totalAuditors }} submitted
                                    <i class="ph ph-info text-indigo-400 text-sm"></i>
                                </p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden min-w-[72px]">
                                        <div class="h-full rounded-full transition-all duration-300
                                            @if($percent >= 100) bg-emerald-500
                                            @elseif($percent >= 50) bg-amber-500
                                            @else bg-rose-500 @endif"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-500">{{ $percent }}%</span>
                                </div>
                            </button>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        @include('audit-events.partials.submission-status-badge', ['status' => $event->submissionStatus()])
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button
                                type="button"
                                class="qr-trigger p-2 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors"
                                title="QR code"
                                aria-label="Show QR code"
                                data-qr-url="{{ url(route('audits.submit', $event->id)) }}"
                                data-event-id="{{ $event->id }}"
                                data-event-title="{{ $event->title }}"
                            >
                                <i class="ph ph-qr-code text-lg"></i>
                            </button>
                            <a href="{{ route('audit-events.show', $event->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                            <a href="{{ route('audit-events.edit', $event->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <form id="delete-event-{{ $event->id }}" action="{{ route('audit-events.destroy', $event->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-event-{{ $event->id }}', 'this audit event')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-8 text-center text-slate-500 font-medium">
                        No audit events scheduled yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
        {{ $events->links() }}
    </div>
</div>

<div id="qr-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="qr-modal-title">
    <div id="qr-modal-content" class="bg-white rounded-3xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden transform scale-95 transition-transform duration-300" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="min-w-0 pr-4">
                <h3 id="qr-modal-title" class="text-lg font-extrabold text-slate-900 truncate">QR Code</h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Scan to open the audit submission page</p>
            </div>
            <button type="button" id="qr-modal-close" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors shrink-0" aria-label="Close">
                <i class="ph ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 flex flex-col items-center gap-4">
            <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <canvas id="qr-canvas"></canvas>
            </div>
            <p id="qr-url-text" class="text-xs font-medium text-slate-500 break-all text-center px-2"></p>
            <div class="flex flex-col sm:flex-row gap-3 w-full">
                <button type="button" id="qr-copy-link" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm transition-colors">
                    <i class="ph ph-copy text-lg"></i> Copy link
                </button>
                <button type="button" id="qr-download" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-primary hover:opacity-95 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-500/25">
                    <i class="ph ph-download-simple text-lg"></i> Download PNG
                </button>
            </div>
        </div>
    </div>
</div>

<div id="submission-popover" class="hidden fixed z-[9999] w-72 bg-white border border-slate-200 rounded-xl shadow-xl p-4" role="dialog" aria-label="Submission details">
    <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Submission Details</p>
        <button type="button" id="submission-popover-close" class="p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors" aria-label="Close">
            <i class="ph ph-x text-sm"></i>
        </button>
    </div>
    <div id="submission-popover-body"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const { createApp, ref } = Vue;
        createApp({
            setup() {
                const search = ref('');
                const statusFilter = ref('');
                
                const filterRow = (title, status) => {
                    const searchMatch = !search.value || title.includes(search.value.toLowerCase());
                    const statusMatch = !statusFilter.value || status === statusFilter.value;
                    return searchMatch && statusMatch;
                };
                
                return { search, statusFilter, filterRow };
            }
        }).mount('#module-app');

        const popover = document.getElementById('submission-popover');
        const popoverBody = document.getElementById('submission-popover-body');
        const popoverClose = document.getElementById('submission-popover-close');
        let activeTrigger = null;

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function avatarUrl(name, color) {
            return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) + '&background=' + color + '&color=fff&size=32';
        }

        function renderAuditorList(names, type) {
            if (!names.length) return '';

            const isSubmitted = type === 'submitted';
            const label = isSubmitted ? 'Submitted' : 'Not Submitted';
            const icon = isSubmitted ? 'ph-check-circle' : 'ph-clock';
            const labelClass = isSubmitted ? 'text-emerald-600' : 'text-slate-400';
            const textClass = isSubmitted ? 'text-slate-700' : 'text-slate-500';
            const bgColor = isSubmitted ? '10B981' : 'CBD5E1';

            const items = names.map(name =>
                '<li class="text-xs font-semibold ' + textClass + ' flex items-center gap-2">' +
                    '<img class="h-5 w-5 rounded-full" src="' + avatarUrl(name, bgColor) + '" alt="' + escapeHtml(name) + '"/>' +
                    escapeHtml(name) +
                '</li>'
            ).join('');

            return '<div class="mb-3 last:mb-0">' +
                '<p class="text-[10px] font-bold ' + labelClass + ' uppercase tracking-wider mb-1.5 flex items-center gap-1">' +
                    '<i class="ph ' + icon + '"></i> ' + label + ' (' + names.length + ')' +
                '</p>' +
                '<ul class="space-y-1">' + items + '</ul>' +
            '</div>';
        }

        function populatePopover(submitted, pending, percent) {
            let html = renderAuditorList(submitted, 'submitted') + renderAuditorList(pending, 'pending');

            if (percent >= 100) {
                html += '<p class="text-[10px] font-bold text-emerald-600 mt-3 pt-3 border-t border-slate-100">All auditors have submitted.</p>';
            }

            if (!html) {
                html = '<p class="text-xs font-medium text-slate-500">No auditor data available.</p>';
            }

            popoverBody.innerHTML = html;
        }

        function positionPopover(trigger) {
            popover.classList.remove('hidden');
            popover.style.visibility = 'hidden';

            const rect = trigger.getBoundingClientRect();
            const popoverRect = popover.getBoundingClientRect();
            const gap = 8;
            const padding = 12;

            let top = rect.bottom + gap;
            let left = rect.left;

            if (left + popoverRect.width > window.innerWidth - padding) {
                left = window.innerWidth - popoverRect.width - padding;
            }
            if (left < padding) {
                left = padding;
            }

            if (top + popoverRect.height > window.innerHeight - padding) {
                top = rect.top - popoverRect.height - gap;
            }
            if (top < padding) {
                top = padding;
            }

            popover.style.top = top + 'px';
            popover.style.left = left + 'px';
            popover.style.visibility = 'visible';
        }

        function closePopover() {
            popover.classList.add('hidden');
            popover.style.visibility = '';
            if (activeTrigger) {
                activeTrigger.classList.remove('ring-2', 'ring-indigo-500/30', 'bg-indigo-50/80');
                activeTrigger.setAttribute('aria-expanded', 'false');
                activeTrigger = null;
            }
        }

        function openPopover(trigger) {
            const submitted = JSON.parse(trigger.dataset.submitted || '[]');
            const pending = JSON.parse(trigger.dataset.pending || '[]');
            const percent = parseInt(trigger.dataset.percent || '0', 10);

            if (activeTrigger === trigger) {
                closePopover();
                return;
            }

            if (activeTrigger) {
                activeTrigger.classList.remove('ring-2', 'ring-indigo-500/30', 'bg-indigo-50/80');
                activeTrigger.setAttribute('aria-expanded', 'false');
            }

            activeTrigger = trigger;
            trigger.classList.add('ring-2', 'ring-indigo-500/30', 'bg-indigo-50/80');
            trigger.setAttribute('aria-expanded', 'true');

            populatePopover(submitted, pending, percent);
            positionPopover(trigger);
        }

        document.querySelectorAll('.submission-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                openPopover(this);
            });
        });

        popoverClose.addEventListener('click', function(e) {
            e.stopPropagation();
            closePopover();
        });

        document.addEventListener('click', function(e) {
            if (!popover.classList.contains('hidden') &&
                !popover.contains(e.target) &&
                !e.target.closest('.submission-trigger')) {
                closePopover();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopover();
                closeQrModal();
            }
        });

        window.addEventListener('scroll', closePopover, true);
        window.addEventListener('resize', function() {
            if (activeTrigger && !popover.classList.contains('hidden')) {
                positionPopover(activeTrigger);
            }
        });

        const qrModal = document.getElementById('qr-modal');
        const qrModalContent = document.getElementById('qr-modal-content');
        const qrModalClose = document.getElementById('qr-modal-close');
        const qrCanvas = document.getElementById('qr-canvas');
        const qrUrlText = document.getElementById('qr-url-text');
        const qrModalTitle = document.getElementById('qr-modal-title');
        const qrCopyLink = document.getElementById('qr-copy-link');
        const qrDownload = document.getElementById('qr-download');
        let currentQrUrl = '';
        let currentEventId = '';

        function closeQrModal() {
            if (qrModal.classList.contains('hidden')) {
                return;
            }

            qrModal.classList.add('opacity-0');
            qrModalContent.classList.add('scale-95');

            setTimeout(function() {
                qrModal.classList.add('hidden');
                qrModal.classList.remove('flex');
                const ctx = qrCanvas.getContext('2d');
                ctx.clearRect(0, 0, qrCanvas.width, qrCanvas.height);
                qrUrlText.textContent = '';
                currentQrUrl = '';
                currentEventId = '';
            }, 300);
        }

        function openQrModal(url, title, eventId) {
            closePopover();
            currentQrUrl = url;
            currentEventId = eventId;
            qrModalTitle.textContent = title;
            qrUrlText.textContent = url;

            QRCode.toCanvas(qrCanvas, url, { width: 256, margin: 2 }, function(error) {
                if (error) {
                    Toast.fire({ icon: 'error', title: 'Failed to generate QR code' });
                    return;
                }

                qrModal.classList.remove('hidden');
                qrModal.classList.add('flex');
                void qrModal.offsetWidth;
                qrModal.classList.remove('opacity-0');
                qrModalContent.classList.remove('scale-95');
            });
        }

        document.querySelectorAll('.qr-trigger').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                openQrModal(
                    trigger.dataset.qrUrl,
                    trigger.dataset.eventTitle,
                    trigger.dataset.eventId
                );
            });
        });

        qrModalClose.addEventListener('click', closeQrModal);

        qrModal.addEventListener('click', function(e) {
            if (e.target === qrModal) {
                closeQrModal();
            }
        });

        qrCopyLink.addEventListener('click', function() {
            if (!currentQrUrl) {
                return;
            }

            navigator.clipboard.writeText(currentQrUrl).then(function() {
                Toast.fire({ icon: 'success', title: 'Link copied to clipboard' });
            }).catch(function() {
                const range = document.createRange();
                range.selectNodeContents(qrUrlText);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                Toast.fire({ icon: 'info', title: 'Select the link and copy manually' });
            });
        });

        qrDownload.addEventListener('click', function() {
            if (!qrCanvas.width) {
                return;
            }

            const link = document.createElement('a');
            link.download = 'audit-event-' + currentEventId + '-qr.png';
            link.href = qrCanvas.toDataURL('image/png');
            link.click();
        });
    });
</script>
@endsection
