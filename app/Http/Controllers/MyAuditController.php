<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AuditEvent;
use App\Models\AuditFinding;
use Illuminate\Support\Facades\Auth;

class MyAuditController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        // Fetch events assigned to this user, including their submitted findings
        $events = AuditEvent::whereHas('auditors', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['project', 'findings' => function($q) use ($userId) {
            $q->where('user_id', $userId);
        }])->latest()->paginate(10);
        
        return view('audits.index', compact('events'));
    }

    public function show($id)
    {
        $auditEvent = AuditEvent::with(['project', 'auditors'])->findOrFail($id);
        
        $finding = AuditFinding::where('audit_event_id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        // Prevent resubmission if already submitted unless edit request is approved
        if ($finding && $finding->edit_request_status !== 'approved') {
            return redirect()->route('audits.index')->with('error', 'You have already submitted an audit for this event. Request edit if you need to make changes.');
        }

        $oldScore = '';
        $oldDescription = '';
        
        if ($finding) {
            $parts = explode("\n\n", $finding->description, 2);
            if (count($parts) == 2 && str_starts_with($parts[0], 'Score: ')) {
                $oldScore = str_replace('Score: ', '', $parts[0]);
                $oldDescription = $parts[1];
            } else {
                $oldDescription = $finding->description;
            }
        }

        return view('audits.submit', compact('auditEvent', 'finding', 'oldScore', 'oldDescription'));
    }

    public function viewSubmission($id)
    {
        $auditEvent = AuditEvent::with(['project', 'auditors'])->findOrFail($id);

        $isAssigned = $auditEvent->auditors->contains('id', Auth::id());
        if (!$isAssigned) {
            abort(403);
        }

        $finding = AuditFinding::where('audit_event_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$finding) {
            return redirect()->route('audits.submit', $id);
        }

        return view('audits.show', compact('auditEvent', 'finding'));
    }
    
    public function requestEdit($id)
    {
        $finding = AuditFinding::where('audit_event_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($finding->edit_request_status !== 'pending') {
            $finding->update(['edit_request_status' => 'pending']);
        }

        return redirect()->back()->with('success', 'Edit request has been submitted to the administrator.');
    }
}
