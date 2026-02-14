<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::query()
            ->with(['category', 'stocks.warehouse'])
            ->latest()
            ->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'internal_code' => ['required', 'string', 'max:100', 'unique:products,internal_code'],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'unit_measure' => ['required', 'string', 'max:50'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock_minimum' => ['required', 'numeric', 'min:0'],
            'stock_maximum' => ['nullable', 'numeric', 'min:0'],
            'shelf_location' => ['nullable', 'string', 'max:100'],
            'expiry_date' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        return response()->json(Product::create($data), 201);
    }

    public function show(Product $product)
    {
        return $product->load(['category', 'stocks.warehouse', 'stockMovements']);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'internal_code' => ['sometimes', 'string', 'max:100', 'unique:products,internal_code,' . $product->id],
            'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode,' . $product->id],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'unit_measure' => ['sometimes', 'string', 'max:50'],
            'purchase_price' => ['sometimes', 'numeric', 'min:0'],
            'sale_price' => ['sometimes', 'numeric', 'min:0'],
            'stock_minimum' => ['sometimes', 'numeric', 'min:0'],
            'stock_maximum' => ['nullable', 'numeric', 'min:0'],
            'shelf_location' => ['nullable', 'string', 'max:100'],
            'expiry_date' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $product->update($data);

        return $product->fresh();
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}

