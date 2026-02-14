@extends('layouts.app')
@section('title', 'Novo Fornecedor')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.suppliers.store') }}">
        @csrf
        <div class="row">
            <div><label>Nome</label><input name="name" required value="{{ old('name') }}"></div>
            <div><label>NUIT</label><input name="nuit" value="{{ old('nuit') }}"></div>
            <div><label>Pessoa de contacto</label><input name="contact_person" value="{{ old('contact_person') }}"></div>
        </div>
        <div class="row">
            <div><label>Telefone</label><input name="phone" value="{{ old('phone') }}"></div>
            <div><label>Email</label><input type="email" name="email" value="{{ old('email') }}"></div>
            <div><label>Endereco</label><input name="address" value="{{ old('address') }}"></div>
        </div>
        <label><input type="checkbox" name="is_active" value="1" checked style="width:auto"> Ativo</label>
        <div><button class="btn btn-primary" type="submit">Guardar</button></div>
    </form>
</div>
@endsection

