<?php

namespace App\Http\Controllers\Api;

use App\Enums\MovementType;
use App\Enums\TransferStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\WarehouseTransfer;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseTransferController extends Controller
{
    public function __construct(private readonly StockService $stockService)
    {
    }

    public function index()
    {
        return WarehouseTransfer::query()
            ->with(['product', 'originWarehouse', 'destinationWarehouse'])
            ->latest('requested_at')
            ->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'origin_warehouse_id' => ['required', 'different:destination_warehouse_id', 'exists:warehouses,id'],
            'destination_warehouse_id' => ['required', 'exists:warehouses,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $transfer = WarehouseTransfer::create([
            ...$data,
            'status' => TransferStatus::PENDING,
            'requested_by' => $request->user()?->id,
            'requested_at' => now(),
        ]);

        return response()->json($transfer, 201);
    }

    public function show(WarehouseTransfer $warehouseTransfer)
    {
        return $warehouseTransfer->load(['product', 'originWarehouse', 'destinationWarehouse']);
    }

    public function update(Request $request, WarehouseTransfer $warehouseTransfer)
    {
        if ($warehouseTransfer->status !== TransferStatus::PENDING) {
            return response()->json(['message' => 'Only pending transfers can be edited.'], 422);
        }

        $data = $request->validate([
            'quantity' => ['sometimes', 'numeric', 'gt:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $warehouseTransfer->update($data);

        return $warehouseTransfer->fresh();
    }

    public function destroy(WarehouseTransfer $warehouseTransfer)
    {
        if ($warehouseTransfer->status !== TransferStatus::PENDING) {
            return response()->json(['message' => 'Only pending transfers can be canceled.'], 422);
        }

        $warehouseTransfer->update(['status' => TransferStatus::CANCELLED]);

        return response()->noContent();
    }

    public function confirm(WarehouseTransfer $warehouseTransfer, Request $request)
    {
        if ($warehouseTransfer->status !== TransferStatus::PENDING) {
            return response()->json(['message' => 'Transfer already processed.'], 422);
        }

        DB::transaction(function () use ($warehouseTransfer, $request) {
            $product = Product::query()->lockForUpdate()->findOrFail($warehouseTransfer->product_id);
            $quantity = (float) $warehouseTransfer->quantity;

            $this->stockService->change($product, (int) $warehouseTransfer->origin_warehouse_id, -$quantity);
            $this->stockService->change($product, (int) $warehouseTransfer->destination_warehouse_id, $quantity);

            $warehouseTransfer->update([
                'status' => TransferStatus::CONFIRMED,
                'confirmed_by' => $request->user()?->id,
                'confirmed_at' => now(),
            ]);

            StockMovement::create([
                'product_id' => $warehouseTransfer->product_id,
                'warehouse_id' => $warehouseTransfer->origin_warehouse_id,
                'type' => MovementType::TRANSFER_OUT,
                'quantity' => $quantity,
                'reference_type' => 'warehouse_transfer',
                'reference_id' => $warehouseTransfer->id,
                'performed_by' => $request->user()?->id,
                'performed_at' => now(),
            ]);

            StockMovement::create([
                'product_id' => $warehouseTransfer->product_id,
                'warehouse_id' => $warehouseTransfer->destination_warehouse_id,
                'type' => MovementType::TRANSFER_IN,
                'quantity' => $quantity,
                'reference_type' => 'warehouse_transfer',
                'reference_id' => $warehouseTransfer->id,
                'performed_by' => $request->user()?->id,
                'performed_at' => now(),
            ]);
        });

        return $warehouseTransfer->fresh();
    }
}

