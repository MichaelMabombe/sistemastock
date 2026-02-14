@extends('layouts.app')
@section('title', 'Usuarios e Permissoes')
@section('content')
<div class="panel">
    <h3>Novo usuario</h3>
    <form method="POST" action="{{ route('web.users.store') }}">
        @csrf
        <div class="row">
            <div><label>Nome</label><input name="name" required></div>
            <div><label>Email</label><input type="email" name="email" required></div>
            <div><label>Senha</label><input type="password" name="password" required></div>
        </div>
        <div class="row">
            <div><label>Perfil</label><select name="role">@foreach($roles as $role)<option value="{{ $role }}">{{ $role }}</option>@endforeach</select></div>
            <div><label>Armazem</label><select name="warehouse_id"><option value="">Sem vinculo</option>@foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>@endforeach</select></div>
            <div><label><input type="checkbox" name="is_active" value="1" checked style="width:auto"> Ativo</label></div>
        </div>
        <button class="btn btn-primary" type="submit">Criar usuario</button>
    </form>
</div>

<div class="panel">
    <table>
        <thead><tr><th>Nome</th><th>Email</th><th>Perfil</th><th>Armazem</th><th>Ativo</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->value ?? $user->role }}</td>
                <td>{{ $user->warehouse->name ?? '-' }}</td>
                <td>{{ $user->is_active ? 'Sim' : 'Nao' }}</td>
                <td>
                    <details>
                        <summary>Editar</summary>
                        <form method="POST" action="{{ route('web.users.update', $user) }}">
                            @csrf @method('PUT')
                            <input name="name" value="{{ $user->name }}" required>
                            <input type="email" name="email" value="{{ $user->email }}" required>
                            <input type="password" name="password" placeholder="Nova senha (opcional)">
                            <select name="role">@foreach($roles as $role)<option value="{{ $role }}" {{ ($user->role->value ?? $user->role) === $role ? 'selected' : '' }}>{{ $role }}</option>@endforeach</select>
                            <select name="warehouse_id"><option value="">Sem vinculo</option>@foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}" {{ (int)$user->warehouse_id === (int)$warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>@endforeach</select>
                            <label><input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} style="width:auto"> Ativo</label>
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        </form>
                    </details>
                    <form class="inline" method="POST" action="{{ route('web.users.destroy', $user) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Apagar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection

