@extends('layouts.app')
@section('title', 'Novo Produto')
@section('content')
<div class="panel">
    <form method="POST" action="{{ route('web.products.store') }}">
        @csrf
        @include('web.products.partials.form', ['product' => null])
        <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
</div>
@endsection

