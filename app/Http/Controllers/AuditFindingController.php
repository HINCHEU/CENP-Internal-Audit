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

        $evidencePath = null;
        if ($request->hasFile('evidence_file')) {
            $evidencePath = $request->file('evidence_file')->store('evidence', 'public');
        }

        AuditFinding::create([
            'audit_event_id' => $validated['audit_event_id'],
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'finding_type' => $validated['finding_type'],
            'description' => "Score: " . $validated['score'] . "\n\n" . $validated['description'],
            'evidence_file_path' => $evidencePath,
            'status' => 'open'
        ]);

        return redirect()->route('audits.index')->with('success', 'Audit findings submitted successfully.');
    }
}
