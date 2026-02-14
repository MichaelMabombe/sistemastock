@extends('layouts.app')
@section('title', 'Editar Categoria')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.categories.update', $category) }}">
        @csrf @method('PUT')
        <label>Nome</label>
        <input name="name" required value="{{ old('name', $category->name) }}">
        <label>Descricao</label>
        <textarea name="description">{{ old('description', $category->description) }}</textarea>
        <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} style="width:auto"> Ativa</label>
        <div><button class="btn btn-primary" type="submit">Atualizar</button></div>
    </form>
</div>
@endsection

