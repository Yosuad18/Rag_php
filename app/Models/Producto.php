<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Producto extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'productos'; // Nombre exacto de tu colección

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'status',
        'embedding',
    ];
}
