<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\Caixa;
use App\Models\ContasReceber;
use App\Models\NotaFiscal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = now()->startOfDay();
        $mesAtual = now()->startOfMonth();

        $vendasHoje = Venda::whereDate('data_venda', today())
            ->sum('valor_total_final');

        $vendasMes = Venda::whereMonth('data_venda', now()->month)
            ->whereYear('data_venda', now()->year)
            ->sum('valor_total_final');

        $totalVendas = Venda::count();
        $totalClientes = Cliente::count();
        $totalProdutos = Produto::count();
        $caixasAbertos = Caixa::where('status', 'aberto')->count();
        
        $contasPendentes = ContasReceber::where('status', 'pendente')
            ->sum('valor_pago');

        $produtosEstoqueBaixo = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->where('estoque_minimo', '>', 0)
            ->count();

        $vendasRecentes = Venda::with(['cliente', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $produtosMaisVendidos = DB::table('venda_items')
            ->join('produtos', 'venda_items.produto_id', '=', 'produtos.id')
            ->select('produtos.nome', DB::raw('SUM(venda_items.quantidade) as total_vendido'))
            ->groupBy('produtos.id', 'produtos.nome')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        $nfePendentes = NotaFiscal::where('status', 'digitacao')->count();

        return view('dashboard', [
            'vendasHoje' => $vendasHoje,
            'vendasMes' => $vendasMes,
            'totalVendas' => $totalVendas,
            'totalClientes' => $totalClientes,
            'totalProdutos' => $totalProdutos,
            'caixasAbertos' => $caixasAbertos,
            'contasPendentes' => $contasPendentes,
            'produtosEstoqueBaixo' => $produtosEstoqueBaixo,
            'vendasRecentes' => $vendasRecentes,
            'produtosMaisVendidos' => $produtosMaisVendidos,
            'nfePendentes' => $nfePendentes
        ]);
    }
}

