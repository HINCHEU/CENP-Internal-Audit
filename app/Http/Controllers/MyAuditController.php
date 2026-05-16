<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AuditEvent;

class MyAuditController extends Controller
{
    public function index()
    {
        // Ideally fetch only assigned to Auth::user(), for now fetch all
        $events = AuditEvent::with('project')->latest()->paginate(10);
        return view('audits.index', compact('events'));
    }

    public function show($id)
    {
        $auditEvent = AuditEvent::with(['project', 'auditors'])->findOrFail($id);
        return view('audits.submit', compact('auditEvent'));
    }
}
