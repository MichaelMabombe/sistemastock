<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SistemaStock</title>
    <style>
        :root {
            --bg: #f2f6f2;
            --sidebar: #163125;
            --panel: #ffffff;
            --text: #132115;
            --muted: #607463;
            --accent: #198754;
            --line: #d4e2d7;
            --danger: #b02a37;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Tahoma, sans-serif; background: var(--bg); color: var(--text); }
        .app { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
        aside { background: var(--sidebar); color: #e9f5ed; padding: 20px 14px; }
        .brand { font-weight: 700; font-size: 22px; margin-bottom: 20px; }
        .nav a { display: block; color: #d6eadc; text-decoration: none; padding: 10px 12px; border-radius: 8px; margin-bottom: 6px; }
        .nav a:hover, .nav a.active { background: #224538; color: #fff; }
        main { padding: 24px; }
        .panel { background: var(--panel); border: 1px solid var(--line); border-radius: 12px; padding: 16px; margin-bottom: 16px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .topbar h1 { margin: 0; font-size: 22px; }
        .btn { border: 0; border-radius: 8px; padding: 9px 12px; cursor: pointer; font-weight: 600; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-muted { background: #e7efe9; color: #224533; }
        .grid { display: grid; gap: 12px; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        .kpi { padding: 14px; border: 1px solid var(--line); border-radius: 10px; background: #fbfdfb; }
        .kpi strong { display: block; font-size: 24px; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid var(--line); padding: 10px 8px; text-align: left; font-size: 14px; }
        form.inline { display: inline; }
        input, select, textarea { width: 100%; padding: 9px 10px; border: 1px solid #bcd0c2; border-radius: 8px; margin-top: 5px; margin-bottom: 10px; }
        label { font-size: 13px; color: #304c38; }
        .row { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 10px; }
        .flash { padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; }
        .flash.success { background: #e1f4e7; color: #1d5e37; border: 1px solid #b6dbbf; }
        .flash.error { background: #f8e3e6; color: #7f1f2a; border: 1px solid #e8bcc3; }
        .errors { background: #fff2f4; border: 1px solid #efc9cf; color: #8a2330; padding: 10px; border-radius: 8px; margin-bottom: 12px; }
        @media (max-width: 900px) { .app { grid-template-columns: 1fr; } aside { position: sticky; top: 0; z-index: 10; } }
    </style>
</head>
<body>
<div class="app">
    <aside>
        <div class="brand">SistemaStock</div>
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('web.products.index') }}" class="{{ request()->routeIs('web.products.*') ? 'active' : '' }}">Produtos</a>
            <a href="{{ route('web.categories.index') }}" class="{{ request()->routeIs('web.categories.*') ? 'active' : '' }}">Categorias</a>
            <a href="{{ route('web.warehouses.index') }}" class="{{ request()->routeIs('web.warehouses.*') ? 'active' : '' }}">Armazens</a>
            <a href="{{ route('web.suppliers.index') }}" class="{{ request()->routeIs('web.suppliers.*') ? 'active' : '' }}">Fornecedores</a>
            <a href="{{ route('web.supplier-transactions.index') }}" class="{{ request()->routeIs('web.supplier-transactions.*') ? 'active' : '' }}">Financeiro fornecedores</a>
            <a href="{{ route('web.stock-movements.index') }}" class="{{ request()->routeIs('web.stock-movements.*') ? 'active' : '' }}">Movimentos</a>
            <a href="{{ route('web.transfers.index') }}" class="{{ request()->routeIs('web.transfers.*') ? 'active' : '' }}">Transferencias</a>
            <a href="{{ route('web.inventory.index') }}" class="{{ request()->routeIs('web.inventory.*') ? 'active' : '' }}">Inventario</a>
            <a href="{{ route('web.alerts.index') }}" class="{{ request()->routeIs('web.alerts.*') ? 'active' : '' }}">Alertas</a>
            <a href="{{ route('web.reports.index') }}" class="{{ request()->routeIs('web.reports.*') ? 'active' : '' }}">Relatorios</a>
            <a href="{{ route('web.users.index') }}" class="{{ request()->routeIs('web.users.*') ? 'active' : '' }}">Usuarios</a>
        </nav>
    </aside>
    <main>
        <div class="topbar">
            <h1>@yield('title', 'SistemaStock')</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger" type="submit">Sair</button>
            </form>
        </div>
        @if(session('success'))<div class="flash success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="flash error">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="errors">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
