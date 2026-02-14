@extends('layouts.app')
@section('title', 'Nova Categoria')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.categories.store') }}">
        @csrf
        <label>Nome</label>
        <input name="name" required value="{{ old('name') }}">
        <label>Descricao</label>
        <textarea name="description">{{ old('description') }}</textarea>
        <label><input type="checkbox" name="is_active" value="1" checked style="width:auto"> Ativa</label>
        <div><button class="btn btn-primary" type="submit">Guardar</button></div>
    </form>
</div>
@endsection

