<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditEvent extends Model {
    protected $fillable = [
        'title',
        'project_id',
        'audit_date',
        'audit_time',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function auditors()
    {
        return $this->belongsToMany(User::class, 'audit_event_user', 'audit_event_id', 'user_id');
    }

    public function findings()
    {
        return $this->hasMany(AuditFinding::class);
    }

    /**
     * pending: no assigned auditor has submitted yet
     * in_progress: at least one submitted, not all
     * completed: every assigned auditor has submitted
     */
    public function submissionStatus(): string
    {
        if (! $this->relationLoaded('auditors')) {
            $this->load('auditors');
        }
        if (! $this->relationLoaded('findings')) {
            $this->load('findings');
        }

        $total = $this->auditors->count();
        if ($total === 0) {
            return 'pending';
        }

        $submittedUserIds = $this->findings->pluck('user_id')->unique();
        $submittedCount = $this->auditors->whereIn('id', $submittedUserIds)->count();

        if ($submittedCount === 0) {
            return 'pending';
        }

        if ($submittedCount >= $total) {
            return 'completed';
        }

        return 'in_progress';
    }
}
