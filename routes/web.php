<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\InventoryCountController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\StockAlertController;
use App\Http\Controllers\Web\StockMovementController;
use App\Http\Controllers\Web\SupplierController;
use App\Http\Controllers\Web\SupplierTransactionController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\WarehouseController;
use App\Http\Controllers\Web\WarehouseTransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::resource('categorias', CategoryController::class)
        ->except(['show'])
        ->names('web.categories')
        ->parameters(['categorias' => 'category']);
    Route::resource('armazens', WarehouseController::class)
        ->except(['show'])
        ->names('web.warehouses')
        ->parameters(['armazens' => 'warehouse']);
    Route::resource('fornecedores', SupplierController::class)
        ->except(['show'])
        ->names('web.suppliers')
        ->parameters(['fornecedores' => 'supplier']);
    Route::resource('usuarios', UserController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('web.users')
        ->parameters(['usuarios' => 'user']);
    Route::resource('produtos', ProductController::class)
        ->except(['show'])
        ->names('web.products')
        ->parameters(['produtos' => 'product']);

    Route::get('movimentos-stock', [StockMovementController::class, 'index'])->name('web.stock-movements.index');
    Route::post('movimentos-stock', [StockMovementController::class, 'store'])->name('web.stock-movements.store');

    Route::get('transferencias', [WarehouseTransferController::class, 'index'])->name('web.transfers.index');
    Route::post('transferencias', [WarehouseTransferController::class, 'store'])->name('web.transfers.store');
    Route::post('transferencias/{warehouseTransfer}/confirmar', [WarehouseTransferController::class, 'confirm'])->name('web.transfers.confirm');
    Route::post('transferencias/{warehouseTransfer}/cancelar', [WarehouseTransferController::class, 'cancel'])->name('web.transfers.cancel');

    Route::get('inventarios', [InventoryCountController::class, 'index'])->name('web.inventory.index');
    Route::post('inventarios', [InventoryCountController::class, 'store'])->name('web.inventory.store');
    Route::get('inventarios/{inventoryCount}', [InventoryCountController::class, 'show'])->name('web.inventory.show');
    Route::post('inventarios/{inventoryCount}/fechar', [InventoryCountController::class, 'close'])->name('web.inventory.close');

    Route::get('alertas', [StockAlertController::class, 'index'])->name('web.alerts.index');
    Route::post('alertas', [StockAlertController::class, 'store'])->name('web.alerts.store');
    Route::post('alertas/{stockAlert}/resolver', [StockAlertController::class, 'resolve'])->name('web.alerts.resolve');
    Route::delete('alertas/{stockAlert}', [StockAlertController::class, 'destroy'])->name('web.alerts.destroy');

    Route::get('financeiro-fornecedores', [SupplierTransactionController::class, 'index'])->name('web.supplier-transactions.index');
    Route::post('financeiro-fornecedores', [SupplierTransactionController::class, 'store'])->name('web.supplier-transactions.store');

    Route::get('relatorios', [ReportController::class, 'index'])->name('web.reports.index');
});
