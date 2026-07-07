<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    protected $fillable = ['evaluation_id', 'user_id', 'department_id', 'evaluator_name', 'evaluator_type', 'score', 'comment', 'excluded'];

    protected $casts = ['excluded' => 'boolean'];


    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
