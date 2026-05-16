<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\AuditEvent;
use App\Models\AuditFinding;

class ReportController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $totalEvents = AuditEvent::count();
        $totalFindings = AuditFinding::count();
        
        $findingsByType = AuditFinding::selectRaw('finding_type, count(*) as total')
            ->groupBy('finding_type')
            ->pluck('total', 'finding_type')->toArray();
            
        $findingsByStatus = AuditFinding::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();

        $recentFindings = AuditFinding::with(['auditEvent.project', 'auditor'])->latest()->take(5)->get();

        $chartData = [
            'major' => $findingsByType['Major Non-conformance'] ?? 0,
            'minor' => $findingsByType['Minor Non-conformance'] ?? 0,
            'observation' => $findingsByType['Observation'] ?? 0,
        ];

        $statusData = [
            'open' => $findingsByStatus['open'] ?? 0,
            'resolved' => $findingsByStatus['resolved'] ?? 0,
            'closed' => $findingsByStatus['closed'] ?? 0,
        ];

        return view('reports.index', compact(
            'totalProjects', 'totalEvents', 'totalFindings', 'chartData', 'statusData', 'recentFindings'
        ));
    }
}
