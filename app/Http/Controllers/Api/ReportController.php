<?php

namespace App\Http\Controllers\Api;

use App\Enums\MovementType;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $startDate = Carbon::parse($request->query('start_date', now()->startOfMonth()));
        $endDate = Carbon::parse($request->query('end_date', now()->endOfMonth()));

        $entries = StockMovement::query()
            ->where('type', MovementType::ENTRY)
            ->whereBetween('performed_at', [$startDate, $endDate])
            ->sum('quantity');

        $exits = StockMovement::query()
            ->whereIn('type', [
                MovementType::SALE,
                MovementType::INTERNAL_USE,
                MovementType::LOSS,
                MovementType::TRANSFER_OUT,
            ])
            ->whereBetween('performed_at', [$startDate, $endDate])
            ->sum('quantity');

        $estimatedStockValue = Product::query()
            ->select(DB::raw('SUM(stock_current * purchase_price) as total'))
            ->value('total') ?? 0;

        $estimatedGrossMargin = Product::query()
            ->select(DB::raw('SUM(stock_current * (sale_price - purchase_price)) as total'))
            ->value('total') ?? 0;

        $topProducts = StockMovement::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('type', MovementType::SALE)
            ->whereBetween('performed_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product:id,name,internal_code')
            ->limit(5)
            ->get();

        return [
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'kpis' => [
                'entries' => (float) $entries,
                'exits' => (float) $exits,
                'estimated_stock_value' => (float) $estimatedStockValue,
                'estimated_gross_margin' => (float) $estimatedGrossMargin,
            ],
            'top_products' => $topProducts,
        ];
    }
}

