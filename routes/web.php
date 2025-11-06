<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// SEUS CONTROLLERS (Nós precisamos de todos eles)
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FornecedorController;
// (Não precisamos mais do UsuarioController, pois o Breeze cuida disso)
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\VendaController; // <-- A ROTA PERDIDA

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota pública (página inicial)
Route::get('/', function () {
    return view('welcome');
});

// --- ROTAS PROTEGIDAS (SÓ PARA QUEM ESTÁ LOGADO) ---
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categorias', CategoriaController::class);
    Route::resource('produtos', ProdutoController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('fornecedores', FornecedorController::class);
    Route::resource('forma_pagamentos', FormaPagamentoController::class);

    Route::get('/vendas', [VendaController::class, 'index'])->name('vendas.index');
    Route::get('/vendas/create', [VendaController::class, 'create'])->name('vendas.create');
    Route::post('/vendas', [VendaController::class, 'store'])->name('vendas.store');
    Route::get('/vendas/{id}', [VendaController::class, 'show'])->name('vendas.show');

});
// --- FIM DAS ROTAS PROTEGIDAS ---



require __DIR__.'/auth.php';