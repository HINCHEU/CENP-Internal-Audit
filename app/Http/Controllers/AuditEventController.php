<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AuditEvent;
use App\Models\Project;
use App\Models\User;

class AuditEventController extends Controller
{
    public function index()
    {
        $events = AuditEvent::with(['project', 'auditors', 'findings'])->latest()->paginate(10);
        return view('audit-events.index', compact('events'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        // Assume 'normal_user' role corresponds to auditors, but for now we fetch all active users except admins or all
        $auditors = User::where('status', 'active')->get();
        
        return view('audit-events.form', [
            'auditEvent' => new AuditEvent(),
            'projects' => $projects,
            'auditors' => $auditors
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'audit_date' => 'required|date',
            'audit_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'auditor_ids' => 'required|array',
            'auditor_ids.*' => 'exists:users,id',
        ]);

        $event = AuditEvent::create([
            'title' => $validated['title'],
            'project_id' => $validated['project_id'],
            'audit_date' => $validated['audit_date'],
            'audit_time' => $validated['audit_time'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $event->auditors()->sync($validated['auditor_ids']);

        return redirect()->route('audit-events.index')->with('success', 'Audit Event created successfully.');
    }

    public function edit(AuditEvent $auditEvent)
    {
        $projects = Project::where('status', 'active')->get();
        $auditors = User::where('status', 'active')->get();
        
        return view('audit-events.form', compact('auditEvent', 'projects', 'auditors'));
    }

    public function update(Request $request, AuditEvent $auditEvent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'audit_date' => 'required|date',
            'audit_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'auditor_ids' => 'required|array',
            'auditor_ids.*' => 'exists:users,id',
        ]);

        $auditEvent->update([
            'title' => $validated['title'],
            'project_id' => $validated['project_id'],
            'audit_date' => $validated['audit_date'],
            'audit_time' => $validated['audit_time'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $auditEvent->auditors()->sync($validated['auditor_ids']);

        return redirect()->route('audit-events.index')->with('success', 'Audit Event updated successfully.');
    }

    public function show(AuditEvent $auditEvent)
    {
        $auditEvent->load(['project', 'auditors.department', 'findings.auditor']);

        $findings = $auditEvent->findings;
        $auditors = $auditEvent->auditors;

        // Calculate overall average score
        $scores = $findings->map(fn ($finding) => $finding->parsedScore())->filter(fn ($score) => $score !== null);
        $overallAverageScore = $scores->count() > 0 ? round($scores->avg(), 2) : 0;
        $scoredFindings = $scores->count();

        // Calculate department scores
        $departmentScoreStats = $auditors
            ->groupBy(fn (User $user) => $user->department_id ?? 0)
            ->map(function ($group) use ($findings) {
                $first = $group->first();
                $label = $first->department?->name ?? 'No department';
                $departmentUserIds = $group->pluck('id');
                
                $deptFindings = $findings->whereIn('user_id', $departmentUserIds);
                $deptScores = $deptFindings->map(fn ($finding) => $finding->parsedScore())->filter(fn ($score) => $score !== null);
                $avgScore = $deptScores->count() > 0 ? round($deptScores->avg(), 2) : 0;
                $count = $deptScores->count();

                return [
                    'label' => $label,
                    'average_score' => $avgScore,
                    'count' => $count,
                ];
            })
            ->values()
            ->sortBy('label')
            ->values()
            ->all();

        $departmentAverageScore = count($departmentScoreStats) > 0
            ? round(collect($departmentScoreStats)->avg('average_score'), 2)
            : 0;

        return view('audit-events.show', compact(
            'auditEvent',
            'overallAverageScore',
            'scoredFindings',
            'departmentScoreStats',
            'departmentAverageScore'
        ));
    }

    public function destroy(AuditEvent $auditEvent)
    {
        $auditEvent->delete();
        return redirect()->route('audit-events.index')->with('success', 'Audit Event deleted successfully.');
    }
}
