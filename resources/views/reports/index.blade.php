@extends('layouts.app')

@section('title', 'Reports & Analytics - CE&P Internal Audit System')
@section('header', 'Reports & Analytics')
@section('subheader', 'Generate insights and detailed reports for audit activities.')

@section('content')
<div class="space-y-8">
    
    <!-- Top Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-6 flex items-center gap-6 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-50 rounded-full blur-2xl group-hover:bg-indigo-100 transition-colors"></div>
            <div class="w-14 h-14 bg-indigo-50 rounded-2xl text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100 relative z-10">
                <i class="ph ph-briefcase text-2xl font-bold"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Total Projects</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalProjects }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-6 flex items-center gap-6 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-purple-50 rounded-full blur-2xl group-hover:bg-purple-100 transition-colors"></div>
            <div class="w-14 h-14 bg-purple-50 rounded-2xl text-purple-600 flex items-center justify-center shrink-0 border border-purple-100 relative z-10">
                <i class="ph ph-calendar-check text-2xl font-bold"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Total Audits</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalEvents }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-6 flex items-center gap-6 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-50 rounded-full blur-2xl group-hover:bg-rose-100 transition-colors"></div>
            <div class="w-14 h-14 bg-rose-50 rounded-2xl text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 relative z-10">
                <i class="ph ph-warning-circle text-2xl font-bold"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Total Findings</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalFindings }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-6 flex items-center gap-6 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 rounded-full blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 relative z-10">
                <i class="ph ph-check-circle text-2xl font-bold"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-1">Resolved</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $statusData['resolved'] + $statusData['closed'] }}</h3>
            </div>
        </div>

    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Findings By Type -->
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 flex flex-col">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-chart-pie-slice text-indigo-500 text-xl"></i> Findings Classification
            </h3>
            <div class="flex-1 flex items-center justify-center relative min-h-[300px]" id="chart-type">
                <!-- ApexChart injects here -->
            </div>
        </div>

        <!-- Findings By Status -->
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8 flex flex-col">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                <i class="ph ph-chart-donut text-purple-500 text-xl"></i> Resolution Status
            </h3>
            <div class="flex-1 flex items-center justify-center relative min-h-[300px]" id="chart-status">
                <!-- ApexChart injects here -->
            </div>
        </div>

    </div>

    <!-- Recent Findings Table -->
    <div class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                <i class="ph ph-clock-counter-clockwise text-indigo-500 text-xl"></i> Recent Findings Log
            </h3>
            <button class="text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition-colors">View All</button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-[10px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                        <th class="px-6 py-4">Finding Details</th>
                        <th class="px-6 py-4">Audit Event</th>
                        <th class="px-6 py-4">Severity</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Edit Request</th>
                        <th class="px-6 py-4 text-right">Date Logged</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentFindings as $finding)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-slate-800 font-bold text-sm line-clamp-1 max-w-[300px]">{{ explode("\n", $finding->description)[0] }}</p>
                            <p class="text-[11px] font-semibold text-slate-500 mt-0.5">By {{ $finding->auditor->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium text-sm">
                            <span class="block truncate max-w-[200px]">{{ $finding->auditEvent->title }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if(str_contains(strtolower($finding->finding_type), 'major'))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">Major</span>
                            @elseif(str_contains(strtolower($finding->finding_type), 'minor'))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200">Minor</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-200">Obsv</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($finding->status == 'open')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-rose-600 bg-rose-50">Open</span>
                            @elseif($finding->status == 'resolved')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-amber-600 bg-amber-50">Resolved</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold text-emerald-600 bg-emerald-50">Closed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($finding->edit_request_status === 'pending')
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('audit-findings.approve-edit', $finding->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition-colors">Approve</button>
                                    </form>
                                    <form action="{{ route('audit-findings.approve-edit', $finding->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-lg transition-colors">Reject</button>
                                    </form>
                                </div>
                            @elseif($finding->edit_request_status === 'approved')
                                <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-md">Approved</span>
                            @elseif($finding->edit_request_status === 'rejected')
                                <span class="text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-md">Rejected</span>
                            @else
                                <span class="text-xs font-medium text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium text-slate-500">
                            {{ $finding->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('audit-findings.show', $finding->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors inline-block" title="View Details">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">No findings recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ApexCharts Integration -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- Findings By Type Chart ---
        const typeOptions = {
            series: [{{ $chartData['major'] }}, {{ $chartData['minor'] }}, {{ $chartData['observation'] }}],
            labels: ['Major Non-conformance', 'Minor Non-conformance', 'Observation'],
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: ['#F43F5E', '#F59E0B', '#3B82F6'], // rose, amber, blue
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                fontSize: '14px',
                                fontWeight: 700,
                                color: '#64748b'
                            },
                            value: {
                                fontSize: '32px',
                                fontWeight: 800,
                                color: '#0f172a',
                                formatter: function (val) {
                                    return val
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                fontSize: '14px',
                                fontWeight: 700,
                                color: '#64748b',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            legend: {
                position: 'bottom',
                fontSize: '13px',
                fontWeight: 600,
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            }
        };

        const typeChart = new ApexCharts(document.querySelector("#chart-type"), typeOptions);
        typeChart.render();


        // --- Findings By Status Chart ---
        const statusOptions = {
            series: [{{ $statusData['open'] }}, {{ $statusData['resolved'] }}, {{ $statusData['closed'] }}],
            labels: ['Open', 'Resolved', 'Closed'],
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'inherit',
            },
            colors: ['#F43F5E', '#8B5CF6', '#10B981'], // rose, purple, emerald
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                fontSize: '14px',
                                fontWeight: 700,
                                color: '#64748b'
                            },
                            value: {
                                fontSize: '32px',
                                fontWeight: 800,
                                color: '#0f172a'
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total'
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            legend: {
                position: 'bottom',
                fontSize: '13px',
                fontWeight: 600,
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            }
        };

        const statusChart = new ApexCharts(document.querySelector("#chart-status"), statusOptions);
        statusChart.render();

    });
</script>
@endsection
