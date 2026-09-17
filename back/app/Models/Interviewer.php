<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Interviewer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'interviewers';

    protected $fillable = [
        'name',
        'email',
        'department',
        'role',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'interviewer_id');
    }
}
