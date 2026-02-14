# SistemaStock

Sistema de gestao de stock para controlar entradas, saidas, transferencias, inventario fisico, alertas e visao financeira.

## Stack

- Backend: Laravel 11 (API REST)
- Banco: MySQL (ou SQLite para testes locais)
- Auth pronta para evoluir com Sanctum

## Modulos implementados (fase 1)

- Cadastro base: categorias, produtos, armazens, fornecedores
- Movimentos de stock: entrada, venda, consumo interno, perdas, ajustes
- Transferencia entre armazens com status e confirmacao
- Inventario fisico com ajuste automatico ao fechar contagem
- Alertas de stock
- Dashboard de relatorios (entradas, saidas, valorizacao e margem estimada)
- Perfis de utilizador: admin, warehouse_manager, operator

## Modelo de dados principal

- `products`
- `product_stocks` (stock por armazem)
- `stock_movements`
- `warehouse_transfers`
- `inventory_counts`
- `inventory_count_items`
- `stock_alerts`
- `suppliers`
- `supplier_transactions`

## API disponivel

Prefixo: `api/`

- `categories` (CRUD)
- `warehouses` (CRUD)
- `suppliers` (CRUD)
- `products` (CRUD)
- `stock-movements` (index/store/show/update)
- `transfers` (CRUD + `POST /transfers/{warehouseTransfer}/confirm`)
- `inventory-counts` (CRUD + `POST /inventory-counts/{inventoryCount}/close`)
- `stock-alerts` (CRUD)
- `GET /reports/dashboard`

## Arranque rapido

1. Instalar dependencias:
```bash
composer install
```

2. Configurar `.env` (base de dados).

3. Executar migrations e seeders:
```bash
php artisan migrate --seed
```

4. Levantar API:
```bash
php artisan serve
```

## Dados iniciais

Seeders criados:
- Categorias basicas
- Armazens iniciais
- Utilizador admin

Credenciais admin:
- Email: `admin@sistemastock.test`
- Password: `admin123`

## Proxima fase recomendada

1. Integrar painel administrativo com Filament.
2. Adicionar autenticacao/autorizacao por perfil (policies + middleware).
3. Criar exportacao PDF/Excel.
4. Integrar frontend React + dashboard com graficos.
5. Preparar PWA e modo offline.

