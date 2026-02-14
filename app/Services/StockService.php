<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function change(Product $product, int $warehouseId, float $quantityDelta): ProductStock
    {
        $stock = ProductStock::query()
            ->where('product_id', $product->id)
            ->where('warehouse_id', $warehouseId)
            ->lockForUpdate()
            ->first();

        if (! $stock) {
            $stock = ProductStock::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouseId,
                'quantity' => 0,
            ])->fresh();
        }

        $newQuantity = (float) $stock->quantity + $quantityDelta;
        if ($newQuantity < 0) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock for this operation.',
            ]);
        }

        $stock->update(['quantity' => $newQuantity]);
        $this->syncGlobalStock($product);

        return $stock->fresh();
    }

    public function syncGlobalStock(Product $product): void
    {
        $total = ProductStock::query()
            ->where('product_id', $product->id)
            ->sum('quantity');

        $product->update(['stock_current' => $total]);
    }
}

