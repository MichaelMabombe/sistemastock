@extends('layouts.app')
@section('title', 'Categorias')
@section('content')
<div class="panel"><a class="btn btn-primary" href="{{ route('web.categories.create') }}">Nova categoria</a></div>
<div class="panel">
    <table>
        <thead><tr><th>Nome</th><th>Descricao</th><th>Ativa</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>{{ $category->is_active ? 'Sim' : 'Nao' }}</td>
                <td>
                    <a class="btn btn-muted" href="{{ route('web.categories.edit', $category) }}">Editar</a>
                    <form class="inline" method="POST" action="{{ route('web.categories.destroy', $category) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Apagar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
@endsection

