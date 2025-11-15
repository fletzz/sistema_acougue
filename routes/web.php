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
use App\Http\Controllers\VendaController;
use App\Http\Controllers\NFeController;
use App\Http\Controllers\EmitenteController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\ContasReceberController;
use App\Http\Controllers\EntradaNotaController;
use App\Http\Controllers\AjusteEstoqueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransformacaoController;
use App\Http\Controllers\EntradaMercadoriaController;

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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    Route::resource('emitente', EmitenteController::class);
    Route::get('/nfe', [NFeController::class, 'index'])->name('nfe.index');
    Route::get('/nfe/create/{vendaId?}', [NFeController::class, 'create'])->name('nfe.create');
    Route::post('/nfe', [NFeController::class, 'store'])->name('nfe.store');
    Route::get('/nfe/{id}', [NFeController::class, 'show'])->name('nfe.show');
    Route::post('/nfe/{id}/autorizar', [NFeController::class, 'autorizar'])->name('nfe.autorizar');
    Route::get('/nfe/{id}/xml', [NFeController::class, 'downloadXml'])->name('nfe.xml');

    Route::resource('caixa', CaixaController::class);
    Route::post('/caixa/{id}/abrir', [CaixaController::class, 'abrir'])->name('caixa.abrir');
    Route::post('/caixa/{id}/fechar', [CaixaController::class, 'fechar'])->name('caixa.fechar');
    Route::get('/caixa/{id}/movimentacoes', [CaixaController::class, 'movimentacoes'])->name('caixa.movimentacoes');
    Route::post('/caixa/{id}/movimentacao', [CaixaController::class, 'adicionarMovimentacao'])->name('caixa.movimentacao');

    Route::get('/contas_receber', [ContasReceberController::class, 'index'])->name('contas_receber.index');
    Route::get('/contas_receber/{id}', [ContasReceberController::class, 'show'])->name('contas_receber.show');
    Route::post('/contas_receber/{id}/receber', [ContasReceberController::class, 'receber'])->name('contas_receber.receber');

    Route::resource('entrada_nota', EntradaNotaController::class);
    Route::resource('entrada_mercadoria', EntradaMercadoriaController::class);
    Route::resource('ajuste_estoque', AjusteEstoqueController::class);
    Route::resource('transformacao', TransformacaoController::class);

});
// --- FIM DAS ROTAS PROTEGIDAS ---



require __DIR__.'/auth.php';