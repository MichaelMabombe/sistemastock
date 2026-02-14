<?php

namespace App\Http\Controllers\Web;

use App\Enums\AlertType;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAlert;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockAlertController extends Controller
{
    public function index()
    {
        return view('web.alerts.index', [
            'alerts' => StockAlert::query()->with(['product', 'warehouse'])->latest()->paginate(20),
            'products' => Product::query()->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'types' => array_column(AlertType::cases(), 'value'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'type' => ['required', Rule::in(array_column(AlertType::cases(), 'value'))],
            'severity' => ['required', 'integer', 'between:1,5'],
            'message' => ['required', 'string', 'max:255'],
        ]);

        StockAlert::create($data);

        return back()->with('success', 'Alerta criado.');
    }

    public function resolve(StockAlert $stockAlert, Request $request)
    {
        $stockAlert->update([
            'resolved_at' => now(),
            'resolved_by' => $request->user()?->id,
        ]);

        return back()->with('success', 'Alerta resolvido.');
    }

    public function destroy(StockAlert $stockAlert)
    {
        $stockAlert->delete();

        return back()->with('success', 'Alerta removido.');
    }
}
