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
        'edit_request_status',
    ];

    public function auditEvent()
    {
        return $this->belongsTo(AuditEvent::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parsedScore(): ?int
    {
        $parts = explode("\n\n", $this->description, 2);
        if (count($parts) === 2 && str_starts_with($parts[0], 'Score: ')) {
            return (int) str_replace('Score: ', '', $parts[0]);
        }

        return null;
    }
}
