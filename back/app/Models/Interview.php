<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Interview extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'interviews';

    protected $fillable = [
        'candidate_id',
        'interviewer_id',
        'scheduled_at',
        'duration_minutes',
        'type',
        'status',
        'location',
        'feedback',
        'rating',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'rating' => 'integer',
        'duration_minutes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(Interviewer::class, 'interviewer_id');
    }
}
