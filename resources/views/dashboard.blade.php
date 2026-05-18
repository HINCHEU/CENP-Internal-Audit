@extends('layouts.app')

@section('title', 'Dashboard - CE&P Internal Audit System')
@section('header', 'Dashboard Overview')
@section('subheader', 'Here is what is happening with your projects today.')

@section('content')
<div class="space-y-8">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-2xl p-6 premium-shadow premium-hover transition-all duration-300 relative overflow-hidden group border border-slate-100/50">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500/10 to-transparent blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                    <i class="ph ph-buildings text-2xl"></i>
                </div>
                <span class="flex items-center gap-1 text-xs font-semibold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="ph ph-trend-up"></i>
                </span>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalDepartments }}</p>
                <p class="text-sm font-medium text-slate-500 mt-1">Total Departments</p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-2xl p-6 premium-shadow premium-hover transition-all duration-300 relative overflow-hidden group border border-slate-100/50">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-gradient-to-br from-purple-500/10 to-transparent blur-2xl group-hover:bg-purple-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100">
                    <i class="ph ph-briefcase text-2xl"></i>
                </div>
                <span class="flex items-center gap-1 text-xs font-semibold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="ph ph-trend-up"></i>
                </span>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $activeProjects }}</p>
                <p class="text-sm font-medium text-slate-500 mt-1">Active Projects</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-2xl p-6 premium-shadow premium-hover transition-all duration-300 relative overflow-hidden group border border-slate-100/50">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500/10 to-transparent blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                    <i class="ph ph-check-circle text-2xl"></i>
                </div>
                <span class="flex items-center gap-1 text-xs font-semibold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="ph ph-trend-up"></i>
                </span>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $completedAudits }}</p>
                <p class="text-sm font-medium text-slate-500 mt-1">Completed Audits</p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white rounded-2xl p-6 premium-shadow premium-hover transition-all duration-300 relative overflow-hidden group border border-slate-100/50">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-gradient-to-br from-amber-500/10 to-transparent blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                    <i class="ph ph-clock text-2xl"></i>
                </div>
                <span class="flex items-center gap-1 text-xs font-semibold text-amber-500 bg-amber-50 px-2 py-1 rounded-full">
                    <i class="ph ph-arrows-out-line-horizontal"></i>
                </span>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $pendingAudits }}</p>
                <p class="text-sm font-medium text-slate-500 mt-1">Pending Audits</p>
            </div>
        </div>
    </div>

    <!-- Charts / Activity Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white rounded-3xl premium-shadow border border-slate-100 p-8 flex flex-col relative overflow-hidden">
            <div class="flex flex-col gap-6 mb-6 relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Project Performance Trends</h3>
                        <p class="text-sm text-slate-500 mt-1" id="chart-subtitle">{{ $chart['subtitle'] }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors px-3 py-2 rounded-lg hover:bg-slate-50">
                        <i class="ph ph-arrow-counter-clockwise"></i> Reset filters
                    </a>
                </div>

                <form method="GET" action="{{ route('dashboard') }}" id="chart-filters" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <div>
                        <label for="filter_project" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Project</label>
                        <select name="project_id" id="filter_project" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="">All projects</option>
                            @foreach($filterProjects as $project)
                                <option value="{{ $project->id }}" @selected((string) $filters['project_id'] === (string) $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter_audit" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Audit</label>
                        <select name="audit_id" id="filter_audit" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="">All audits</option>
                            @foreach($filterAudits as $audit)
                                <option value="{{ $audit->id }}" data-project-id="{{ $audit->project_id }}" @selected((string) $filters['audit_id'] === (string) $audit->id)>
                                    {{ $audit->title }} ({{ \Carbon\Carbon::parse($audit->audit_date)->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter_metric" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Metric</label>
                        <select name="metric" id="filter_metric" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="score" @selected($filters['metric'] === 'score')>Average score</option>
                            <option value="submission" @selected($filters['metric'] === 'submission')>Submission rate</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter_date_from" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Date from</label>
                        <input type="date" name="date_from" id="filter_date_from" value="{{ $filters['date_from'] }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="filter_date_to" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Date to</label>
                        <input type="date" name="date_to" id="filter_date_to" value="{{ $filters['date_to'] }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="filter_department" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Submit · department</label>
                        <select name="department_id" id="filter_department" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl bg-white text-slate-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="">All departments</option>
                            @foreach($filterDepartments as $department)
                                <option value="{{ $department->id }}" @selected((string) $filters['department_id'] === (string) $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 xl:col-span-3 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-primary hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/25 transition-all">
                            <i class="ph ph-funnel text-lg"></i> Apply filters
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex-1 min-h-[300px] relative" id="performance-chart">
                @if(empty($chart['labels']))
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                        <i class="ph ph-chart-bar text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-semibold text-slate-500">{{ $chart['subtitle'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-8">Recent Findings</h3>
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-[1.1rem] before:-translate-x-px before:h-full before:w-[2px] before:bg-gradient-to-b before:from-indigo-100 before:via-slate-100 before:to-transparent">
                
                @forelse($recentActivities as $finding)
                <a href="{{ route('audit-findings.show', $finding->id) }}" class="relative flex gap-6 items-start group block cursor-pointer">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full border-[3px] border-white bg-indigo-100 text-indigo-600 shadow-sm shrink-0 z-10 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <i class="ph ph-check-circle text-lg font-bold"></i>
                    </div>
                    <div class="flex-1 bg-slate-50/80 p-4 rounded-xl border border-slate-100/80 group-hover:border-indigo-100 group-hover:bg-indigo-50/30 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-bold text-slate-800 text-sm">Audit Submitted</h4>
                            <span class="text-[11px] font-semibold text-slate-400">{{ $finding->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ $finding->auditor->name }} submitted final report for <span class="text-indigo-600 font-bold">{{ $finding->auditEvent->title ?? 'Event' }}</span></p>
                    </div>
                </a>
                @empty
                <div class="text-sm text-slate-500 font-medium pl-10">No recent activity recorded yet.</div>
                @endforelse
                
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartLabels = @json($chart['labels']);
        const chartValues = @json($chart['values']);
        const chartMetric = @json($chart['metric']);
        const seriesName = chartMetric === 'submission' ? 'Submission Rate (%)' : 'Average Score';

        const projectSelect = document.getElementById('filter_project');
        const auditSelect = document.getElementById('filter_audit');

        if (projectSelect && auditSelect) {
            const auditOptions = Array.from(auditSelect.querySelectorAll('option[data-project-id]'));

            function filterAuditOptions() {
                const projectId = projectSelect.value;
                auditOptions.forEach(function(option) {
                    const show = !projectId || option.dataset.projectId === projectId;
                    option.hidden = !show;
                    option.disabled = !show;
                });
                const selected = auditSelect.selectedOptions[0];
                if (selected && selected.disabled) {
                    auditSelect.value = '';
                }
            }

            projectSelect.addEventListener('change', filterAuditOptions);
            filterAuditOptions();
        }

        if (!chartLabels.length) {
            return;
        }

        const options = {
            series: [{
                name: seriesName,
                data: chartValues
            }],
            chart: {
                type: 'bar',
                height: 350,
                fontFamily: 'inherit',
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    dynamicAnimation: { enabled: true, speed: 350 }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: { enabled: false },
            stroke: { width: 0 },
            xaxis: {
                categories: chartLabels,
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600,
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                max: 100,
                labels: {
                    formatter: function(val) {
                        return chartMetric === 'submission' ? val + '%' : val;
                    },
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600,
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return chartMetric === 'submission' ? val + '%' : val;
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            theme: { palette: 'palette1' },
            colors: ['#6366F1', '#8B5CF6', '#10B981', '#F59E0B', '#F43F5E', '#3B82F6'],
            legend: { show: false }
        };

        const chart = new ApexCharts(document.querySelector("#performance-chart"), options);
        chart.render();
    });
</script>
@endsection
