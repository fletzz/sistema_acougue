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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RelatorioController;

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

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    //Route::get('/checkout', [VendaController::class, 'create'])->name('checkout');
    // DASHBOARD DO CAIXA
    Route::get('/checkout', [VendaController::class, 'index'])->name('checkout');

    // ABRIR CAIXA
    Route::post('/checkout/abrir', [VendaController::class, 'abrirCaixa'])->name('checkout.abrir');

    // TELA DO PDV
    Route::get('/checkout/pdv', [VendaController::class, 'create'])->name('checkout.pdv');
    
    Route::get('/pdv/produtos', function () {
        $query = request('q');

        return \App\Models\Produto::where('nome', 'LIKE', "%$query%")
            ->orWhere('codigo_barras', 'LIKE', "%$query%")
            ->limit(20)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nome' => $p->nome,
                    'preco_venda' => (float) str_replace(',', '.', $p->preco_venda),
                ];
            });
    })->name('pdv.produtos');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categorias', CategoriaController::class);
    Route::resource('produtos', ProdutoController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('fornecedores', FornecedorController::class);
    Route::resource('forma_pagamentos', FormaPagamentoController::class);

    Route::get('/vendas', [VendaController::class, 'index'])->name('vendas.index');
    //Route::get('/vendas/create', [VendaController::class, 'create'])->name('vendas.create');
    Route::post('/vendas', [VendaController::class, 'store'])->name('vendas.store');
    Route::get('/vendas/{id}', [VendaController::class, 'show'])->name('vendas.show');
    Route::post('/vendas/{id}/cancelar', [VendaController::class, 'cancelar'])->name('vendas.cancelar');

    Route::resource('emitente', EmitenteController::class);
    Route::get('/nfe', [NFeController::class, 'index'])->name('nfe.index');
    Route::get('/nfe/create/{vendaId?}', [NFeController::class, 'create'])->name('nfe.create');
    Route::post('/nfe', [NFeController::class, 'store'])->name('nfe.store');
    Route::get('/nfe/{id}', [NFeController::class, 'show'])->name('nfe.show');
    Route::post('/nfe/{id}/autorizar', [NFeController::class, 'autorizar'])->name('nfe.autorizar');
    Route::get('/nfe/{id}/xml', [NFeController::class, 'downloadXml'])->name('nfe.xml');

    Route::post('/vendas/abrir-caixa', [VendaController::class, 'abrirCaixa'])
    ->name('vendas.abrirCaixa');

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

    // Relatórios
    Route::get('/relatorios/vendas', [RelatorioController::class, 'vendas'])->name('relatorios.vendas');
    Route::get('/relatorios/produtos-mais-vendidos', [RelatorioController::class, 'produtosMaisVendidos'])->name('relatorios.produtos_mais_vendidos');
    Route::get('/relatorios/lucratividade', [RelatorioController::class, 'lucratividade'])->name('relatorios.lucratividade');
    Route::get('/relatorios/estoque', [RelatorioController::class, 'estoque'])->name('relatorios.estoque');
    Route::get('/relatorios/fiados', [RelatorioController::class, 'fiados'])->name('relatorios.fiados');

});
// --- FIM DAS ROTAS PROTEGIDAS ---



require __DIR__.'/auth.php';