<?php

namespace App\Models;

use App\Enums\TransferStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_warehouse_id',
        'destination_warehouse_id',
        'product_id',
        'quantity',
        'status',
        'requested_by',
        'confirmed_by',
        'requested_at',
        'confirmed_at',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'status' => TransferStatus::class,
        'requested_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function originWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'origin_warehouse_id');
    }

    public function destinationWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
