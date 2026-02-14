@extends('layouts.app')
@section('title', 'Editar Fornecedor')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.suppliers.update', $supplier) }}">
        @csrf @method('PUT')
        <div class="row">
            <div><label>Nome</label><input name="name" required value="{{ old('name', $supplier->name) }}"></div>
            <div><label>NUIT</label><input name="nuit" value="{{ old('nuit', $supplier->nuit) }}"></div>
            <div><label>Pessoa de contacto</label><input name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"></div>
        </div>
        <div class="row">
            <div><label>Telefone</label><input name="phone" value="{{ old('phone', $supplier->phone) }}"></div>
            <div><label>Email</label><input type="email" name="email" value="{{ old('email', $supplier->email) }}"></div>
            <div><label>Endereco</label><input name="address" value="{{ old('address', $supplier->address) }}"></div>
        </div>
        <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }} style="width:auto"> Ativo</label>
        <div><button class="btn btn-primary" type="submit">Atualizar</button></div>
    </form>
</div>
@endsection

