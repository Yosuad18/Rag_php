<?php

namespace App\Models;

use Database\Factories\PagoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model; 
use MongoDB\Laravel\Relations\BelongsTo;

class Pago extends Model
{

    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'pagos';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'status',
        'description',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
