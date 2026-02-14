<?php

namespace App\Http\Controllers\Web;

use App\Enums\MovementType;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
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
        return view('web.stock-movements.index', [
            'movements' => StockMovement::query()->with(['product', 'warehouse', 'user'])->latest('performed_at')->paginate(20),
            'products' => Product::query()->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'types' => array_column(MovementType::cases(), 'value'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'type' => ['required', Rule::in(array_column(MovementType::cases(), 'value'))],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'direction' => ['nullable', Rule::in(['in', 'out'])],
        ]);

        DB::transaction(function () use ($data, $request) {
            $product = Product::query()->lockForUpdate()->findOrFail($data['product_id']);
            $type = MovementType::from($data['type']);
            $delta = match ($type) {
                MovementType::ENTRY, MovementType::TRANSFER_IN => (float) $data['quantity'],
                MovementType::SALE, MovementType::INTERNAL_USE, MovementType::LOSS, MovementType::TRANSFER_OUT => -(float) $data['quantity'],
                MovementType::ADJUSTMENT, MovementType::INVENTORY_ADJUSTMENT => ($data['direction'] ?? 'in') === 'out'
                    ? -(float) $data['quantity']
                    : (float) $data['quantity'],
            };

            $this->stockService->change($product, (int) $data['warehouse_id'], $delta);

            StockMovement::create([
                'product_id' => $data['product_id'],
                'warehouse_id' => $data['warehouse_id'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'unit_price' => $data['unit_price'] ?? null,
                'total_value' => isset($data['unit_price']) ? ((float) $data['unit_price'] * (float) $data['quantity']) : null,
                'notes' => $data['notes'] ?? null,
                'performed_by' => $request->user()?->id,
                'performed_at' => now(),
            ]);
        });

        return back()->with('success', 'Movimento registado com sucesso.');
    }
}
