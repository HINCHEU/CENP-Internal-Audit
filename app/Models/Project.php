<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    protected $fillable = [
        'project_code',
        'name',
        'department_id',
        'project_manager_id',
        'location',
        'start_date',
        'end_date',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function auditEvents()
    {
        return $this->hasMany(AuditEvent::class);
    }
}
