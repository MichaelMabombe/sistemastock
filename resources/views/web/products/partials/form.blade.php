<div class="row">
    <div><label>Nome</label><input name="name" required value="{{ old('name', $product?->name) }}"></div>
    <div><label>Codigo interno</label><input name="internal_code" required value="{{ old('internal_code', $product?->internal_code) }}"></div>
    <div><label>Codigo barras</label><input name="barcode" value="{{ old('barcode', $product?->barcode) }}"></div>
</div>
<div class="row">
    <div>
        <label>Categoria</label>
        <select name="category_id" required>
            <option value="">Selecione</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (string) old('category_id', $product?->category_id) === (string) $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div><label>Marca</label><input name="brand" value="{{ old('brand', $product?->brand) }}"></div>
    <div><label>Unidade</label><input name="unit_measure" required value="{{ old('unit_measure', $product?->unit_measure ?? 'un') }}"></div>
</div>
<div class="row">
    <div><label>Preco compra</label><input type="number" step="0.01" name="purchase_price" required value="{{ old('purchase_price', $product?->purchase_price ?? 0) }}"></div>
    <div><label>Preco venda</label><input type="number" step="0.01" name="sale_price" required value="{{ old('sale_price', $product?->sale_price ?? 0) }}"></div>
    <div><label>Stock minimo</label><input type="number" step="0.001" name="stock_minimum" required value="{{ old('stock_minimum', $product?->stock_minimum ?? 0) }}"></div>
</div>
<div class="row">
    <div><label>Stock maximo</label><input type="number" step="0.001" name="stock_maximum" value="{{ old('stock_maximum', $product?->stock_maximum) }}"></div>
    <div><label>Localizacao</label><input name="shelf_location" value="{{ old('shelf_location', $product?->shelf_location) }}"></div>
    <div><label>Validade</label><input type="date" name="expiry_date" value="{{ old('expiry_date', optional($product?->expiry_date)->format('Y-m-d')) }}"></div>
</div>
<label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }} style="width:auto"> Ativo</label>

