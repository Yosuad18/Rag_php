<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Producto extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'productos';

    protected $fillable = [
        'nombre',
        'precio',
        'atributos',
    ];

    protected $casts = [
        'precio' => 'integer',
        'atributos' => 'array',
    ];
}
