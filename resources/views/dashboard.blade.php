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
        
        <!-- Main Chart Placeholder -->
        <div class="lg:col-span-2 bg-white rounded-3xl premium-shadow border border-slate-100 p-8 flex flex-col relative overflow-hidden">
            <div class="flex items-center justify-between mb-8 relative z-10">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Project Performance Trends</h3>
                    <p class="text-sm text-slate-500 mt-1">Average estimated audit scores across projects.</p>
                </div>
            </div>
            <div class="flex-1 min-h-[300px] relative" id="performance-chart">
                <!-- ApexChart will inject here -->
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-8">Recent Findings</h3>
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-[1.1rem] before:-translate-x-px before:h-full before:w-[2px] before:bg-gradient-to-b before:from-indigo-100 before:via-slate-100 before:to-transparent">
                
                @forelse($recentActivities as $finding)
                <div class="relative flex gap-6 items-start group">
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
                </div>
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
        const options = {
            series: [{
                name: 'Average Score',
                data: {!! json_encode($chartScores) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                fontFamily: 'inherit',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            xaxis: {
                categories: {!! json_encode($chartLabels) !!},
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600,
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                max: 100,
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600,
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            theme: {
                palette: 'palette1'
            },
            colors: ['#6366F1', '#8B5CF6', '#10B981', '#F59E0B', '#F43F5E', '#3B82F6'],
            legend: {
                show: false
            }
        };

        const chart = new ApexCharts(document.querySelector("#performance-chart"), options);
        chart.render();
    });
</script>
@endsection
