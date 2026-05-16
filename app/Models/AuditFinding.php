<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model {
    protected $fillable = [
        'audit_event_id',
        'user_id',
        'finding_type',
        'description',
        'evidence_file_path',
        'status',
    ];

    public function auditEvent()
    {
        return $this->belongsTo(AuditEvent::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
