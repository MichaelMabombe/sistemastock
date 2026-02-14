<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\InventoryCountController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StockAlertController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\WarehouseTransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('categories', CategoryController::class);
Route::apiResource('warehouses', WarehouseController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('stock-movements', StockMovementController::class)
    ->except(['destroy'])
    ->parameters(['stock-movements' => 'stockMovement']);
Route::apiResource('transfers', WarehouseTransferController::class)
    ->parameters(['transfers' => 'warehouseTransfer']);
Route::post('transfers/{warehouseTransfer}/confirm', [WarehouseTransferController::class, 'confirm']);
Route::apiResource('inventory-counts', InventoryCountController::class)
    ->parameters(['inventory-counts' => 'inventoryCount']);
Route::post('inventory-counts/{inventoryCount}/close', [InventoryCountController::class, 'close']);
Route::apiResource('stock-alerts', StockAlertController::class)
    ->parameters(['stock-alerts' => 'stockAlert']);
Route::get('reports/dashboard', [ReportController::class, 'dashboard']);
