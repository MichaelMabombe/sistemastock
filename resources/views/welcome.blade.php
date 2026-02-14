<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SistemaStock</title>
    <style>
        :root {
            --bg: #f4f7f2;
            --panel: #ffffff;
            --text: #112015;
            --muted: #4e6a58;
            --accent: #0d7c4a;
            --accent-soft: #d9f0e3;
            --border: #d8e4da;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top right, #e6f6ec 0, var(--bg) 45%);
            color: var(--text);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 16px 48px;
        }

        .hero {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(16, 46, 29, 0.08);
        }

        h1 {
            margin: 0 0 10px;
            font-size: 34px;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
        }

        .status {
            display: inline-block;
            margin-top: 16px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 600;
            font-size: 14px;
        }

        .grid {
            margin-top: 22px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 12px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
        }

        .card strong {
            display: block;
            margin-bottom: 6px;
        }

        .card p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .api {
            margin-top: 28px;
            background: #0f2a1c;
            color: #effaf2;
            border-radius: 14px;
            padding: 18px;
            overflow: auto;
        }

        .api a {
            color: #a4f5c8;
            text-decoration: none;
        }

        .api ul {
            margin: 10px 0 0;
            padding-left: 18px;
        }
    </style>
</head>
<body>
    <main class="container">
        <section class="hero">
            <h1>SistemaStock</h1>
            <p class="subtitle">Gestao de stock, transferencias, inventario fisico e relatorios em uma unica plataforma.</p>
            <span class="status">API online em {{ url('/api') }}</span>

            <div class="grid">
                <article class="card"><strong>Produtos</strong><p>Cadastro, preco, stock minimo e localizacao.</p></article>
                <article class="card"><strong>Entradas e Saidas</strong><p>Movimentos com atualizacao automatica de stock.</p></article>
                <article class="card"><strong>Transferencias</strong><p>Fluxo entre armazens com confirmacao.</p></article>
                <article class="card"><strong>Inventario Fisico</strong><p>Contagem e ajuste automatico de divergencias.</p></article>
                <article class="card"><strong>Alertas</strong><p>Minimo de stock, validade e produtos lentos.</p></article>
                <article class="card"><strong>Relatorios</strong><p>KPIs de entradas, saidas, margem e valorizacao.</p></article>
            </div>
        </section>

        <section class="api">
            <strong>Endpoints principais</strong>
            <ul>
                <li><a href="{{ url('/api/products') }}" target="_blank">GET /api/products</a></li>
                <li><a href="{{ url('/api/stock-movements') }}" target="_blank">GET /api/stock-movements</a></li>
                <li><a href="{{ url('/api/transfers') }}" target="_blank">GET /api/transfers</a></li>
                <li><a href="{{ url('/api/inventory-counts') }}" target="_blank">GET /api/inventory-counts</a></li>
                <li><a href="{{ url('/api/reports/dashboard') }}" target="_blank">GET /api/reports/dashboard</a></li>
            </ul>
        </section>
    </main>
</body>
</html>

