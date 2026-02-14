<?php

namespace App\Http\Controllers\Web;

use App\Enums\MovementType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\StockMovement;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->date('start_date')?->startOfDay() ?? now()->startOfMonth();
        $end = $request->date('end_date')?->endOfDay() ?? now()->endOfMonth();

        $entries = StockMovement::query()
            ->where('type', MovementType::ENTRY)
            ->whereBetween('performed_at', [$start, $end])
            ->sum('quantity');

        $exits = StockMovement::query()
            ->whereIn('type', [MovementType::SALE, MovementType::INTERNAL_USE, MovementType::LOSS, MovementType::TRANSFER_OUT])
            ->whereBetween('performed_at', [$start, $end])
            ->sum('quantity');

        $estimatedValue = Product::query()->select(DB::raw('SUM(stock_current * purchase_price) as total'))->value('total') ?? 0;
        $estimatedMargin = Product::query()->select(DB::raw('SUM(stock_current * (sale_price - purchase_price)) as total'))->value('total') ?? 0;

        $topProducts = StockMovement::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('type', MovementType::SALE)
            ->whereBetween('performed_at', [$start, $end])
            ->groupBy('product_id')
            ->with('product:id,name,internal_code')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return view('web.reports.index', compact(
            'start',
            'end',
            'entries',
            'exits',
            'estimatedValue',
            'estimatedMargin',
            'topProducts'
        ));
    }
}
