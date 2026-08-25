<?php

namespace App\Models;

use Database\Factories\RepartidorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Repartidor extends Model
{
    /** @use HasFactory<RepartidorFactory> */
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'repartidores';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'vehicle_type',
        'license_plate',
        'status',
    ];
}
