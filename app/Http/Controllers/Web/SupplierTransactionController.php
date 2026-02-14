<?php

namespace App\Http\Controllers\Web;

use App\Enums\SupplierTransactionType;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierTransactionController extends Controller
{
    public function index()
    {
        return view('web.supplier-transactions.index', [
            'transactions' => SupplierTransaction::query()
                ->with('supplier')
                ->latest()
                ->paginate(20),
            'suppliers' => Supplier::query()->orderBy('name')->get(),
            'types' => array_column(SupplierTransactionType::cases(), 'value'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'type' => ['required', Rule::in(array_column(SupplierTransactionType::cases(), 'value'))],
            'amount' => ['required', 'numeric', 'gt:0'],
            'reference' => ['nullable', 'string', 'max:100'],
            'due_date' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        SupplierTransaction::create([
            ...$data,
            'user_id' => $request->user()?->id,
        ]);

        return back()->with('success', 'Movimento financeiro registado.');
    }
}
