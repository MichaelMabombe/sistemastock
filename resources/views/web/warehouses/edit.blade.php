@extends('layouts.app')
@section('title', 'Editar Armazem')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.warehouses.update', $warehouse) }}">
        @csrf @method('PUT')
        <div class="row">
            <div><label>Nome</label><input name="name" required value="{{ old('name', $warehouse->name) }}"></div>
            <div><label>Codigo</label><input name="code" required value="{{ old('code', $warehouse->code) }}"></div>
            <div><label>Cidade</label><input name="city" value="{{ old('city', $warehouse->city) }}"></div>
        </div>
        <label>Endereco</label><input name="address" value="{{ old('address', $warehouse->address) }}">
        <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }} style="width:auto"> Ativo</label>
        <div><button class="btn btn-primary" type="submit">Atualizar</button></div>
    </form>
</div>
@endsection

