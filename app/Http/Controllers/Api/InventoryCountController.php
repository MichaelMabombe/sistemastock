<?php

namespace App\Http\Controllers\Api;

use App\Enums\InventoryStatus;
use App\Enums\MovementType;
use App\Http\Controllers\Controller;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryCountController extends Controller
{
    public function __construct(private readonly StockService $stockService)
    {
    }

    public function index()
    {
        return InventoryCount::query()->with(['warehouse', 'items.product'])->latest()->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.physical_quantity' => ['required', 'numeric', 'min:0'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        $count = DB::transaction(function () use ($data, $request) {
            $count = InventoryCount::create([
                'warehouse_id' => $data['warehouse_id'],
                'status' => InventoryStatus::OPEN,
                'started_by' => $request->user()?->id,
                'counted_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $systemQuantity = ProductStock::query()
                    ->where('warehouse_id', $data['warehouse_id'])
                    ->where('product_id', $item['product_id'])
                    ->value('quantity') ?? 0;

                InventoryCountItem::create([
                    'inventory_count_id' => $count->id,
                    'product_id' => $item['product_id'],
                    'system_quantity' => $systemQuantity,
                    'physical_quantity' => $item['physical_quantity'],
                    'difference_quantity' => (float) $item['physical_quantity'] - (float) $systemQuantity,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return $count;
        });

        return response()->json($count->load(['items.product', 'warehouse']), 201);
    }

    public function show(InventoryCount $inventoryCount)
    {
        return $inventoryCount->load(['items.product', 'warehouse']);
    }

    public function update(Request $request, InventoryCount $inventoryCount)
    {
        if ($inventoryCount->status !== InventoryStatus::OPEN) {
            return response()->json(['message' => 'Closed inventories cannot be edited.'], 422);
        }

        $data = $request->validate([
            'notes' => ['nullable', 'string'],
            'items' => ['sometimes', 'array'],
            'items.*.id' => ['required_with:items', 'exists:inventory_count_items,id'],
            'items.*.physical_quantity' => ['required_with:items', 'numeric', 'min:0'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data, $inventoryCount) {
            if (isset($data['notes'])) {
                $inventoryCount->update(['notes' => $data['notes']]);
            }

            foreach ($data['items'] ?? [] as $itemData) {
                $item = InventoryCountItem::query()
                    ->where('inventory_count_id', $inventoryCount->id)
                    ->findOrFail($itemData['id']);

                $item->update([
                    'physical_quantity' => $itemData['physical_quantity'],
                    'difference_quantity' => (float) $itemData['physical_quantity'] - (float) $item->system_quantity,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }
        });

        return $inventoryCount->fresh()->load(['items.product', 'warehouse']);
    }

    public function destroy(InventoryCount $inventoryCount)
    {
        if ($inventoryCount->status !== InventoryStatus::OPEN) {
            return response()->json(['message' => 'Closed inventories cannot be removed.'], 422);
        }

        $inventoryCount->delete();

        return response()->noContent();
    }

    public function close(InventoryCount $inventoryCount, Request $request)
    {
        if ($inventoryCount->status !== InventoryStatus::OPEN) {
            return response()->json(['message' => 'Inventory already closed.'], 422);
        }

        DB::transaction(function () use ($inventoryCount, $request) {
            $inventoryCount->load('items');

            foreach ($inventoryCount->items as $item) {
                if ((float) $item->difference_quantity === 0.0) {
                    continue;
                }

                $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);
                $this->stockService->change($product, (int) $inventoryCount->warehouse_id, (float) $item->difference_quantity);

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $inventoryCount->warehouse_id,
                    'type' => MovementType::INVENTORY_ADJUSTMENT,
                    'quantity' => abs((float) $item->difference_quantity),
                    'reference_type' => 'inventory_count',
                    'reference_id' => $inventoryCount->id,
                    'notes' => 'Auto adjustment from physical inventory count.',
                    'performed_by' => $request->user()?->id,
                    'performed_at' => now(),
                ]);

                $item->update(['adjusted' => true]);
            }

            $inventoryCount->update([
                'status' => InventoryStatus::CLOSED,
                'closed_by' => $request->user()?->id,
                'closed_at' => now(),
            ]);
        });

        return $inventoryCount->fresh()->load(['items.product', 'warehouse']);
    }
}

