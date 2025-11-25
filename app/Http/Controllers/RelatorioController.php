<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Produto;
use App\Models\VendaItem;
use App\Models\ContasReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Relatório de vendas
     */
    public function vendas(Request $request)
    {
        $dataInicio = $request->input('data_inicio', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->input('data_fim', Carbon::today()->format('Y-m-d'));
        $vendedorId = $request->input('vendedor_id');
        $clienteId = $request->input('cliente_id');

        $query = Venda::with(['cliente', 'user', 'items.produto'])
            ->whereBetween('data_venda', [
                Carbon::parse($dataInicio)->startOfDay(),
                Carbon::parse($dataFim)->endOfDay()
            ])
            ->where('status', 'finalizada');

        if ($vendedorId) {
            $query->where('usuario_id', $vendedorId);
        }

        if ($clienteId) {
            $query->where('cliente_id', $clienteId);
        }

        $vendas = $query->orderBy('data_venda', 'desc')->get();

        // Estatísticas
        $totalVendas = $vendas->count();
        $valorTotal = $vendas->sum('valor_total_final');
        $lucroTotal = 0;
        $custoTotal = 0;

        foreach ($vendas as $venda) {
            $lucro = $venda->lucro_total;
            if ($lucro !== null) {
                $lucroTotal += $lucro;
            }
            foreach ($venda->items as $item) {
                if ($item->produto && $item->produto->preco_custo) {
                    $custoTotal += $item->produto->preco_custo * $item->quantidade;
                }
            }
        }

        $margemLucro = $custoTotal > 0 ? ($lucroTotal / $custoTotal) * 100 : 0;

        return view('relatorios.vendas', [
            'vendas' => $vendas,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'totalVendas' => $totalVendas,
            'valorTotal' => $valorTotal,
            'lucroTotal' => $lucroTotal,
            'margemLucro' => $margemLucro
        ]);
    }

    /**
     * Relatório de produtos mais vendidos
     */
    public function produtosMaisVendidos(Request $request)
    {
        $dataInicio = $request->input('data_inicio', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->input('data_fim', Carbon::today()->format('Y-m-d'));
        $limite = $request->input('limite', 10);

        $produtos = VendaItem::select(
                'produto_id',
                DB::raw('SUM(quantidade) as total_quantidade'),
                DB::raw('SUM(quantidade * preco_unitario) as total_vendido'),
                DB::raw('COUNT(DISTINCT venda_id) as total_vendas')
            )
            ->join('vendas', 'venda_items.venda_id', '=', 'vendas.id')
            ->whereBetween('vendas.data_venda', [
                Carbon::parse($dataInicio)->startOfDay(),
                Carbon::parse($dataFim)->endOfDay()
            ])
            ->where('vendas.status', 'finalizada')
            ->groupBy('produto_id')
            ->orderBy('total_quantidade', 'desc')
            ->limit($limite)
            ->with('produto')
            ->get();

        return view('relatorios.produtos_mais_vendidos', [
            'produtos' => $produtos,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim
        ]);
    }

    /**
     * Relatório de lucratividade
     */
    public function lucratividade(Request $request)
    {
        $dataInicio = $request->input('data_inicio', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->input('data_fim', Carbon::today()->format('Y-m-d'));

        $vendas = Venda::with(['items.produto'])
            ->whereBetween('data_venda', [
                Carbon::parse($dataInicio)->startOfDay(),
                Carbon::parse($dataFim)->endOfDay()
            ])
            ->where('status', 'finalizada')
            ->get();

        $lucroPorProduto = [];
        $custoTotal = 0;
        $receitaTotal = 0;
        $lucroTotal = 0;

        foreach ($vendas as $venda) {
            $receitaTotal += $venda->valor_total_final;

            foreach ($venda->items as $item) {
                $produto = $item->produto;
                if ($produto && $produto->preco_custo) {
                    $custoItem = $produto->preco_custo * $item->quantidade;
                    $lucroItem = ($item->preco_unitario - $produto->preco_custo) * $item->quantidade;

                    $custoTotal += $custoItem;
                    $lucroTotal += $lucroItem;

                    if (!isset($lucroPorProduto[$produto->id])) {
                        $lucroPorProduto[$produto->id] = [
                            'produto' => $produto,
                            'quantidade_vendida' => 0,
                            'receita' => 0,
                            'custo' => 0,
                            'lucro' => 0
                        ];
                    }

                    $lucroPorProduto[$produto->id]['quantidade_vendida'] += $item->quantidade;
                    $lucroPorProduto[$produto->id]['receita'] += $item->quantidade * $item->preco_unitario;
                    $lucroPorProduto[$produto->id]['custo'] += $custoItem;
                    $lucroPorProduto[$produto->id]['lucro'] += $lucroItem;
                }
            }
        }

        // Ordenar por lucro
        usort($lucroPorProduto, function($a, $b) {
            return $b['lucro'] <=> $a['lucro'];
        });

        $margemLucro = $custoTotal > 0 ? ($lucroTotal / $custoTotal) * 100 : 0;

        return view('relatorios.lucratividade', [
            'lucroPorProduto' => $lucroPorProduto,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'receitaTotal' => $receitaTotal,
            'custoTotal' => $custoTotal,
            'lucroTotal' => $lucroTotal,
            'margemLucro' => $margemLucro
        ]);
    }

    /**
     * Relatório de estoque
     */
    public function estoque(Request $request)
    {
        $categoriaId = $request->input('categoria_id');
        $estoqueBaixo = $request->input('estoque_baixo', false);
        $proximoVencimento = $request->input('proximo_vencimento', false);
        $vencido = $request->input('vencido', false);

        $query = Produto::with('categoria');

        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($estoqueBaixo) {
            $query->whereColumn('estoque_atual', '<=', 'estoque_minimo');
        }

        if ($proximoVencimento) {
            $query->whereNotNull('data_validade')
                ->where('data_validade', '>=', Carbon::today())
                ->where('data_validade', '<=', Carbon::today()->addDays(7));
        }

        if ($vencido) {
            $query->whereNotNull('data_validade')
                ->where('data_validade', '<', Carbon::today());
        }

        $produtos = $query->orderBy('nome')->get();

        // Estatísticas
        $valorTotalEstoque = 0;
        $valorTotalCusto = 0;

        foreach ($produtos as $produto) {
            if ($produto->preco_venda) {
                $valorTotalEstoque += $produto->preco_venda * $produto->estoque_atual;
            }
            if ($produto->preco_custo) {
                $valorTotalCusto += $produto->preco_custo * $produto->estoque_atual;
            }
        }

        return view('relatorios.estoque', [
            'produtos' => $produtos,
            'valorTotalEstoque' => $valorTotalEstoque,
            'valorTotalCusto' => $valorTotalCusto,
            'categoriaId' => $categoriaId,
            'estoqueBaixo' => $estoqueBaixo,
            'proximoVencimento' => $proximoVencimento,
            'vencido' => $vencido
        ]);
    }

    /**
     * Relatório de fiados
     */
    public function fiados(Request $request)
    {
        $status = $request->input('status', 'pendente');
        $clienteId = $request->input('cliente_id');
        $vencimentoInicio = $request->input('vencimento_inicio');
        $vencimentoFim = $request->input('vencimento_fim');

        $query = ContasReceber::with(['cliente', 'venda']);

        if ($status !== 'todos') {
            $query->where('status', $status);
        }

        if ($clienteId) {
            $query->where('cliente_id', $clienteId);
        }

        if ($vencimentoInicio && $vencimentoFim) {
            $query->whereBetween('data_pagamento', [
                Carbon::parse($vencimentoInicio)->startOfDay(),
                Carbon::parse($vencimentoFim)->endOfDay()
            ]);
        }

        $fiados = $query->orderBy('data_pagamento')->get();

        $totalPendente = ContasReceber::where('status', 'pendente')->sum('valor_pago');
        $totalRecebido = ContasReceber::where('status', 'recebido')->sum('valor_pago');
        $totalCancelado = ContasReceber::where('status', 'cancelada')->sum('valor_pago');

        return view('relatorios.fiados', [
            'fiados' => $fiados,
            'status' => $status,
            'totalPendente' => $totalPendente,
            'totalRecebido' => $totalRecebido,
            'totalCancelado' => $totalCancelado
        ]);
    }
}
