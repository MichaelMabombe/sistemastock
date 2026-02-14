@extends('layouts.app')
@section('title', 'Editar Produto')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.products.update', $product) }}">
        @csrf @method('PUT')
        @include('web.products.partials.form', ['product' => $product])
        <button class="btn btn-primary" type="submit">Atualizar</button>
    </form>
</div>
@endsection

