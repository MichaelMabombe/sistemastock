<?php

namespace App\Http\Controllers;

use App\Enums\MovementType;
use App\Models\Product;
use App\Models\StockAlert;
use App\Models\StockMovement;
use App\Models\WarehouseTransfer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $entries = StockMovement::query()
            ->where('type', MovementType::ENTRY)
            ->whereBetween('performed_at', [$start, $end])
            ->sum('quantity');

        $exits = StockMovement::query()
            ->whereIn('type', [
                MovementType::SALE,
                MovementType::INTERNAL_USE,
                MovementType::LOSS,
                MovementType::TRANSFER_OUT,
            ])
            ->whereBetween('performed_at', [$start, $end])
            ->sum('quantity');

        $stockValue = Product::query()
            ->select(DB::raw('SUM(stock_current * purchase_price) as total'))
            ->value('total') ?? 0;

        $pendingTransfers = WarehouseTransfer::query()
            ->where('status', 'pending')
            ->count();

        $openAlerts = StockAlert::query()
            ->whereNull('resolved_at')
            ->count();

        return view('dashboard', [
            'entries' => $entries,
            'exits' => $exits,
            'stockValue' => $stockValue,
            'pendingTransfers' => $pendingTransfers,
            'openAlerts' => $openAlerts,
            'latestMovements' => StockMovement::query()
                ->with(['product:id,name', 'warehouse:id,name'])
                ->latest('performed_at')
                ->limit(8)
                ->get(),
        ]);
    }
}
