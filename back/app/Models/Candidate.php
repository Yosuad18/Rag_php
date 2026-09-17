<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Candidate extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'candidates';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'position_applied',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'candidate_id');
    }
}
