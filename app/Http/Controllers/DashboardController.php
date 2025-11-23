<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Venda;
use App\Models\ContasReceber;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1) Cards de resumo
        $totalProdutos = Produto::count();

        $estoqueBaixo = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->count();

        // ajuste o campo "data_venda" se na sua tabela for "created_at" ou outro
        $valorTotalHoje = Venda::whereDate('data_venda', Carbon::today())
            ->sum('valor_total_final');

        // contas a receber pendentes (ajuste campo/valor de status se for diferente)
        $fiadosAReceber = ContasReceber::where('status', 'pendente')
            ->sum('valor_pago');

        // 2) Fiados próximos (5 primeiros)
        $fiadosProximos = ContasReceber::with('cliente')
            ->where('status', 'pendente')
            ->orderBy('data_pagamento')
            ->limit(5)
            ->get();

        // 3) Alerta de estoque (4 produtos mais “críticos”)
        $alertaEstoque = Produto::orderByRaw('(estoque_atual - estoque_minimo) asc')
            ->limit(4)
            ->get();

        // 4) Vendas últimos 7 dias (para o gráfico)
        $diasPeriodo = collect(range(6, 0))->map(function ($i) {
            return Carbon::today()->subDays($i);
        });

        // monta arrays alinhados: labels (Seg, Ter...) e valores
        $chartLabels = $diasPeriodo->map(function (Carbon $dia) {
            return ucfirst($dia->locale('pt_BR')->isoFormat('ddd'));
        });

        $valoresPorDia = Venda::whereBetween('data_venda', [
                $diasPeriodo->first()->startOfDay(),
                $diasPeriodo->last()->endOfDay(),
            ])
            ->selectRaw('DATE(data_venda) as data, SUM(valor_total_final) as total')
            ->groupBy('data')
            ->pluck('total', 'data'); // ['2025-11-10' => 123.45, ...]

        $chartValues = $diasPeriodo->map(function (Carbon $dia) use ($valoresPorDia) {
            $key = $dia->toDateString();
            return (float) ($valoresPorDia[$key] ?? 0);
        });

        return view('dashboard', [
            'user'           => $user,
            'totalProdutos'  => $totalProdutos,
            'estoqueBaixo'   => $estoqueBaixo,
            'valorTotalHoje' => $valorTotalHoje,
            'fiadosAReceber' => $fiadosAReceber,
            'fiadosProximos' => $fiadosProximos,
            'alertaEstoque'  => $alertaEstoque,
            'chartLabels'    => $chartLabels,
            'chartValues'    => $chartValues,
        ]);
    }
}
