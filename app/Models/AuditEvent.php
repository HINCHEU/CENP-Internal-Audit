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
}
