<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AuditFinding;

class AuditFindingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'audit_event_id' => 'required|exists:audit_events,id',
            'finding_type' => 'required|string',
            'description' => 'required|string',
            'evidence_file' => 'nullable|file|max:10240|mimes:pdf,jpg,png,docx',
            'score' => 'required|integer|min:0|max:100',
        ]);

        $finding = AuditFinding::where('audit_event_id', $validated['audit_event_id'])
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->first();

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
            'status' => 'open',
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
}
