<?php

namespace App\Http\Controllers\Api;

use App\Enums\AlertType;
use App\Http\Controllers\Controller;
use App\Models\StockAlert;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockAlertController extends Controller
{
    public function index()
    {
        return StockAlert::query()->with(['product', 'warehouse'])->latest()->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'type' => ['required', Rule::in(array_column(AlertType::cases(), 'value'))],
            'severity' => ['sometimes', 'integer', 'between:1,5'],
            'message' => ['required', 'string', 'max:255'],
            'meta' => ['nullable', 'array'],
        ]);

        return response()->json(StockAlert::create($data), 201);
    }

    public function show(StockAlert $stockAlert)
    {
        return $stockAlert->load(['product', 'warehouse']);
    }

    public function update(Request $request, StockAlert $stockAlert)
    {
        $data = $request->validate([
            'severity' => ['sometimes', 'integer', 'between:1,5'],
            'message' => ['sometimes', 'string', 'max:255'],
            'meta' => ['nullable', 'array'],
            'resolved' => ['sometimes', 'boolean'],
        ]);

        if (($data['resolved'] ?? false) === true) {
            $data['resolved_at'] = now();
            $data['resolved_by'] = $request->user()?->id;
        }

        unset($data['resolved']);
        $stockAlert->update($data);

        return $stockAlert->fresh();
    }

    public function destroy(StockAlert $stockAlert)
    {
        $stockAlert->delete();

        return response()->noContent();
    }
}

