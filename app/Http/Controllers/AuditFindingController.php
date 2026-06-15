<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AuditFinding;
use App\Models\AuditEvent;

class AuditFindingController extends Controller
{
    public function index()
    {
        $findings = AuditFinding::with(['auditEvent.project', 'auditor'])
            ->latest()
            ->paginate(20);

        return view('audit-findings.index', compact('findings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'audit_event_id' => 'required|exists:audit_events,id',
            'finding_type' => ['required', 'string', Rule::in(AuditFinding::findingTypes())],
            'description' => 'required|string',
            'evidence_file' => 'nullable|file|max:10240|mimes:pdf,jpg,png,docx',
            'score' => 'required|integer|min:0|max:100',
        ]);

        $auditEvent = AuditEvent::with('auditors')->findOrFail($validated['audit_event_id']);
        if (! $this->userCanAccessAuditEvent($auditEvent)) {
            abort(403);
        }

        $finding = AuditFinding::where('audit_event_id', $validated['audit_event_id'])
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->first();

        if ($finding && $finding->edit_request_status !== 'approved') {
            return redirect()->route('audits.index')->with('error', 'You have already submitted an audit for this event. Request edit if you need to make changes.');
        }

        $evidencePath = $finding ? $finding->evidence_file_path : null;
        if ($request->hasFile('evidence_file')) {
            $evidencePath = $request->file('evidence_file')->store('evidence', 'public');
        }

        $data = [
            'audit_event_id' => $validated['audit_event_id'],
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'finding_type' => $validated['finding_type'],
            'description' => "Score: " . $validated['score'] . "\n\n" . $validated['description'],
            'evidence_file_path' => $evidencePath,
            'status' => AuditFinding::statusForType($validated['finding_type']),
            'edit_request_status' => null, // reset if they resubmit
        ];

        if ($finding) {
            $finding->update($data);
        } else {
            AuditFinding::create($data);
        }

        return redirect()->route('audits.index')->with('success', 'Audit findings submitted successfully.');
    }

    public function approveEdit($id, Request $request)
    {
        $finding = AuditFinding::findOrFail($id);
        
        $action = $request->input('action'); // 'approve' or 'reject'
        
        if ($action === 'approve') {
            $finding->update(['edit_request_status' => 'approved']);
            $msg = 'Edit request approved.';
        } else {
            $finding->update(['edit_request_status' => 'rejected']);
            $msg = 'Edit request rejected.';
        }

        return redirect()->back()->with('success', $msg);
    }
    public function show($id)
    {
        $finding = AuditFinding::with(['auditEvent.project', 'auditEvent.auditors', 'auditEvent.findings', 'auditor'])->findOrFail($id);

        if (! $this->userCanAccessAuditEvent($finding->auditEvent)) {
            abort(403);
        }
        
        // Parse score and description if needed
        $parsedScore = null;
        $parsedDescription = $finding->description;
        
        $parts = explode("\n\n", $finding->description, 2);
        if (count($parts) == 2 && str_starts_with($parts[0], 'Score: ')) {
            $parsedScore = str_replace('Score: ', '', $parts[0]);
            $parsedDescription = $parts[1];
        }

        return view('audit-findings.show', compact('finding', 'parsedScore', 'parsedDescription'));
    }

    private function userCanAccessAuditEvent(AuditEvent $auditEvent): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        if (! $auditEvent->relationLoaded('auditors')) {
            $auditEvent->load('auditors');
        }

        return $auditEvent->auditors->contains('id', $user->id);
    }
}
