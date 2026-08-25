<?php

namespace App\Models;

use Database\Factories\ProductoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'embedding',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'atributos' => 'array',
            'embedding' => 'array',
        ];
    }
}
