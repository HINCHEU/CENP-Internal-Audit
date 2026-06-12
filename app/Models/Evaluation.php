<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['project_id', 'date', 'title', 'description', 'status'];

    protected $casts = [
        'date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
}
