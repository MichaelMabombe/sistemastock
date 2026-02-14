@extends('layouts.app')
@section('title', 'Novo Armazem')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.warehouses.store') }}">
        @csrf
        <div class="row">
            <div><label>Nome</label><input name="name" required value="{{ old('name') }}"></div>
            <div><label>Codigo</label><input name="code" required value="{{ old('code') }}"></div>
            <div><label>Cidade</label><input name="city" value="{{ old('city') }}"></div>
        </div>
        <label>Endereco</label><input name="address" value="{{ old('address') }}">
        <label><input type="checkbox" name="is_active" value="1" checked style="width:auto"> Ativo</label>
        <div><button class="btn btn-primary" type="submit">Guardar</button></div>
    </form>
</div>
@endsection

