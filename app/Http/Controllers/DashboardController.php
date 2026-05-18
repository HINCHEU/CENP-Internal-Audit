<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\Project;
use App\Models\AuditEvent;
use App\Models\AuditFinding;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalDepartments = Department::count();
        $activeProjects = Project::where('status', 'active')->count() ?: Project::count();
        $completedAudits = AuditFinding::count();
        $pendingAudits = AuditEvent::with(['auditors', 'findings'])
            ->get()
            ->filter(fn ($audit) => $audit->submissionStatus() === 'pending')
            ->count();

        $recentActivities = AuditFinding::with(['auditor', 'auditEvent'])->latest()->take(5)->get();

        $filters = [
            'audit_id' => $request->input('audit_id'),
            'project_id' => $request->input('project_id'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'department_id' => $request->input('department_id'),
            'metric' => $request->input('metric', 'score'),
        ];

        $filterAudits = AuditEvent::with('project')
            ->orderByDesc('audit_date')
            ->get(['id', 'title', 'project_id', 'audit_date']);

        $filterProjects = Project::orderBy('name')->get(['id', 'name']);
        $filterDepartments = Department::orderBy('name')->get(['id', 'name']);

        $chart = $this->buildChartData($filters);

        return view('dashboard', compact(
            'totalDepartments',
            'activeProjects',
            'completedAudits',
            'pendingAudits',
            'recentActivities',
            'filters',
            'filterAudits',
            'filterProjects',
            'filterDepartments',
            'chart'
        ));
    }

    private function buildChartData(array $filters): array
    {
        $metric = $filters['metric'] === 'submission' ? 'submission' : 'score';

        $eventsQuery = AuditEvent::with(['project', 'auditors.department', 'findings.auditor']);

        if ($filters['audit_id']) {
            $eventsQuery->where('id', $filters['audit_id']);
        }
        if ($filters['project_id']) {
            $eventsQuery->where('project_id', $filters['project_id']);
        }
        if ($filters['date_from']) {
            $eventsQuery->whereDate('audit_date', '>=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $eventsQuery->whereDate('audit_date', '<=', $filters['date_to']);
        }

        $events = $eventsQuery->get();

        if ($events->isEmpty()) {
            return [
                'labels' => [],
                'values' => [],
                'metric' => $metric,
                'subtitle' => 'No audit events match the selected filters. Adjust project, audit, or date range.',
            ];
        }

        $labels = [];
        $values = [];

        if ($filters['audit_id']) {
            $event = $events->first();
            if ($metric === 'submission') {
                $value = $this->averageSubmissionPercent(collect([$event]), $filters['department_id']);
                if ($value !== null) {
                    $labels[] = $event->title;
                    $values[] = $value;
                }
            } else {
                $auditors = $event->auditors;
                if ($filters['department_id']) {
                    $auditors = $auditors->where('department_id', (int) $filters['department_id']);
                }
                foreach ($auditors as $auditor) {
                    $finding = $event->findings->firstWhere('user_id', $auditor->id);
                    $score = $finding?->parsedScore();
                    if ($score !== null) {
                        $labels[] = $auditor->name;
                        $values[] = $score;
                    }
                }
            }
        } else {
            $groups = $this->groupEventsForChart($events, $filters);

            foreach ($groups as $label => $groupEvents) {
                $value = $metric === 'submission'
                    ? $this->averageSubmissionPercent($groupEvents, $filters['department_id'])
                    : $this->averageScore($groupEvents, $filters['department_id']);

                if ($value !== null) {
                    $labels[] = $label;
                    $values[] = $value;
                }
            }
        }

        if (empty($labels)) {
            return [
                'labels' => [],
                'values' => [],
                'metric' => $metric,
                'subtitle' => $metric === 'submission'
                    ? 'No submission data for the selected filters.'
                    : 'No scored submissions for the selected filters.',
            ];
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'metric' => $metric,
            'subtitle' => $this->chartSubtitle($filters, $metric),
        ];
    }

    private function groupEventsForChart(Collection $events, array $filters): Collection
    {
        if ($filters['project_id']) {
            return $events->groupBy(fn ($event) => $event->title);
        }

        return $events->groupBy(fn ($event) => $event->project?->name ?? 'Unknown Project');
    }

    private function averageScore(Collection $events, ?string $departmentId): ?float
    {
        $scores = collect();

        foreach ($events as $event) {
            foreach ($event->findings as $finding) {
                if ($departmentId && (string) $finding->auditor?->department_id !== (string) $departmentId) {
                    continue;
                }

                $score = $finding->parsedScore();
                if ($score !== null) {
                    $scores->push($score);
                }
            }
        }

        if ($scores->isEmpty()) {
            return null;
        }

        return round($scores->avg(), 1);
    }

    private function averageSubmissionPercent(Collection $events, ?string $departmentId): ?float
    {
        $percents = collect();

        foreach ($events as $event) {
            $auditors = $event->auditors;
            if ($departmentId) {
                $auditors = $auditors->where('department_id', (int) $departmentId);
            }

            $total = $auditors->count();
            if ($total === 0) {
                continue;
            }

            $submittedIds = $event->findings->pluck('user_id');
            $submitted = $auditors->whereIn('id', $submittedIds)->count();
            $percents->push((int) round(($submitted / $total) * 100));
        }

        if ($percents->isEmpty()) {
            return null;
        }

        return round($percents->avg(), 1);
    }

    private function chartSubtitle(array $filters, string $metric): string
    {
        $parts = [];

        if ($metric === 'submission') {
            $parts[] = 'Average submission completion rate';
        } else {
            $parts[] = 'Average audit scores from submissions';
        }

        if ($filters['department_id']) {
            $dept = Department::find($filters['department_id']);
            $parts[] = $dept ? "for auditors in {$dept->name}" : 'for selected department';
        } else {
            $parts[] = 'for all departments';
        }

        if ($filters['audit_id']) {
            $audit = AuditEvent::find($filters['audit_id']);
            if ($audit) {
                $parts[] = "audit: {$audit->title}";
            }
        } elseif ($filters['project_id']) {
            $project = Project::find($filters['project_id']);
            if ($project) {
                $parts[] = "project: {$project->name}";
            }
        }

        if ($filters['date_from'] || $filters['date_to']) {
            $from = $filters['date_from'] ? Carbon::parse($filters['date_from'])->format('M d, Y') : '…';
            $to = $filters['date_to'] ? Carbon::parse($filters['date_to'])->format('M d, Y') : '…';
            $parts[] = "{$from} – {$to}";
        }

        return ucfirst(implode(' · ', $parts)) . '.';
    }
}
