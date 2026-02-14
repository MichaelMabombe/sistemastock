<?php

namespace App\Http\Controllers\Web;

use App\Enums\InventoryStatus;
use App\Enums\MovementType;
use App\Http\Controllers\Controller;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Warehouse;
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
        return view('web.inventory.index', [
            'inventoryCounts' => InventoryCount::query()->with('warehouse')->latest()->paginate(20),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    public function show(InventoryCount $inventoryCount)
    {
        return view('web.inventory.show', [
            'inventoryCount' => $inventoryCount->load(['warehouse', 'items.product']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.physical_quantity' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $count = InventoryCount::create([
                'warehouse_id' => $data['warehouse_id'],
                'status' => InventoryStatus::OPEN,
                'started_by' => $request->user()?->id,
                'counted_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $system = ProductStock::query()
                    ->where('warehouse_id', $data['warehouse_id'])
                    ->where('product_id', $item['product_id'])
                    ->value('quantity') ?? 0;

                InventoryCountItem::create([
                    'inventory_count_id' => $count->id,
                    'product_id' => $item['product_id'],
                    'system_quantity' => $system,
                    'physical_quantity' => $item['physical_quantity'],
                    'difference_quantity' => (float) $item['physical_quantity'] - (float) $system,
                ]);
            }
        });

        return back()->with('success', 'Inventario criado.');
    }

    public function close(InventoryCount $inventoryCount, Request $request)
    {
        if ($inventoryCount->status !== InventoryStatus::OPEN) {
            return back()->with('error', 'Inventario ja fechado.');
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
                    'notes' => 'Ajuste automatico por inventario fisico.',
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

        return back()->with('success', 'Inventario fechado e stock ajustado.');
    }
}
