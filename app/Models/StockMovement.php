<?php

namespace App\Models;

use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'type',
        'quantity',
        'unit_price',
        'total_value',
        'reference_type',
        'reference_id',
        'notes',
        'performed_by',
        'performed_at',
    ];

    protected $casts = [
        'type' => MovementType::class,
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
        'performed_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
