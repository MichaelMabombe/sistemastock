<?php

namespace App\Http\Controllers\Api;

use App\Enums\MovementType;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockMovementController extends Controller
{
    public function __construct(private readonly StockService $stockService)
    {
    }

    public function index()
    {
        return StockMovement::query()
            ->with(['product', 'warehouse', 'user'])
            ->latest('performed_at')
            ->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'type' => ['required', Rule::in(array_column(MovementType::cases(), 'value'))],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_id' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
            'performed_at' => ['nullable', 'date'],
            'direction' => ['nullable', Rule::in(['in', 'out'])],
        ]);

        $movement = DB::transaction(function () use ($data, $request) {
            $product = Product::query()->lockForUpdate()->findOrFail($data['product_id']);
            $type = MovementType::from($data['type']);
            $delta = $this->resolveDelta($type, (float) $data['quantity'], $data['direction'] ?? null);

            $this->stockService->change($product, (int) $data['warehouse_id'], $delta);

            return StockMovement::create([
                ...$data,
                'total_value' => isset($data['unit_price']) ? (float) $data['unit_price'] * (float) $data['quantity'] : null,
                'performed_by' => $request->user()?->id,
                'performed_at' => $data['performed_at'] ?? now(),
            ]);
        });

        return response()->json($movement->load(['product', 'warehouse', 'user']), 201);
    }

    public function show(StockMovement $stockMovement)
    {
        return $stockMovement->load(['product', 'warehouse', 'user']);
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        $data = $request->validate([
            'notes' => ['nullable', 'string'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_id' => ['nullable', 'integer'],
        ]);

        $stockMovement->update($data);

        return $stockMovement->fresh();
    }

    public function destroy(StockMovement $stockMovement)
    {
        return response()->json([
            'message' => 'Stock movement records cannot be deleted for audit integrity.',
        ], 422);
    }

    private function resolveDelta(MovementType $type, float $quantity, ?string $direction): float
    {
        return match ($type) {
            MovementType::ENTRY, MovementType::TRANSFER_IN => $quantity,
            MovementType::SALE,
            MovementType::INTERNAL_USE,
            MovementType::LOSS,
            MovementType::TRANSFER_OUT => -$quantity,
            MovementType::ADJUSTMENT, MovementType::INVENTORY_ADJUSTMENT => $direction === 'out' ? -$quantity : $quantity,
        };
    }
}

