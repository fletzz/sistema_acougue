<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Venda;
use App\Models\VendaItem;
use App\Models\FormaPagamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ContasReceber;
use App\Models\CaixaMovimentacao;
use App\Services\NFeService;
use App\Models\Emitente;
use Illuminate\Support\Facades\Log;


class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $vendas = Venda::with(['cliente', 'user']) 
                        ->latest()
                        ->get();

        return view('vendas.index', ['vendas' => $vendas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();
        $formaPagamentos = FormaPagamento::all();

        // 🔹 NOVO: carregar as últimas vendas para o modal "vendas finalizadas"
        $vendasRecentes = Venda::with(['cliente'])
            ->latest()
            ->limit(20)   // ajuste se quiser mais/menos
            ->get();

        return view('vendas.create', [
            'clientes'         => $clientes,
            'produtos'         => $produtos,
            'formaPagamentos'  => $formaPagamentos,
            'vendasRecentes'   => $vendasRecentes, // 🔹 passar pra view
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'forma_pagamento_id' =>'required|exists:formas_pagamento,id',
            'items' => 'required|string',
            'valor_total_final' => 'required|numeric|min:0'
        ]);

        $itemsArray = json_decode($dadosValidados['items'], true);

        try {
            DB::beginTransaction(); // Inicia o "caixa de segurança"

            $dadosVenda = [
                'cliente_id' => $dadosValidados['cliente_id'],
                //'forma_pagamento_id' => $dadosValidados['forma_pagamento_id'],
                'valor_total_final' => $dadosValidados['valor_total_final'],
                'data_venda' => now(),
                'usuario_id' => Auth::id(), //Pega o id do user logado no sistema
                'status' => 'finalizada',
                'valor_total_produtos' => $dadosValidados['valor_total_final'],
                'valor_desconto' => 0,
                'observacao' => null
            ];

            $venda = Venda::create($dadosVenda); //Criei a venda "pai"

            foreach ($itemsArray as $item) {
                
                // 8.1. Encontra o produto
                $produto = Produto::findOrFail($item['produto_id']);

                // 8.2. DEBUG: VAMOS VER OS VALORES REAIS
                // Vamos converter ambos para string para ter certeza
                $estoque_real_string = (string)$produto->estoque_atual;
                $venda_real_string = (string)$item['quantidade'];

                // 8.3. A CHECAGEM DE ESTOQUE
                // Compara os dois números como strings
                if (bccomp($estoque_real_string, $venda_real_string, 3) === -1) {
                    
                    // Jogue um erro que nos diz OS NÚMEROS REAIS!
                    throw new \Exception('Estoque insuficiente. Estoque no DB: [' . $estoque_real_string . '] | Quantidade da Venda: [' . $venda_real_string . ']');
                }

                // 7. CRIAR O VENDA_ITEM
                $dadosItem = [
                    'venda_id' => $venda->id, 
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'], 
                    'preco_unitario' => $item['preco_unitario'] 
                ];
                VendaItem::create($dadosItem);

                // 8.4. DAR BAIXA NO ESTOQUE (Só acontece se o 'if' acima passar)
                $produto->estoque_atual -= $item['quantidade'];
                $produto->save();
            }

            $dadosConta = [
                'venda_id' => $venda->id,
                'cliente_id' => $dadosValidados['cliente_id'],
                'forma_pagamento_id' => $dadosValidados['forma_pagamento_id'],
                'valor_pago' => $dadosValidados['valor_total_final'],
                'data_pagamento' => $dadosVenda['data_venda'],
                'status' => 'pendente'
            ];
            ContasReceber::create($dadosConta);


            $caixa = \App\Models\Caixa::where('status', 'aberto')->first();
            if ($caixa) {
                $dadosCaixa = [
                    'caixa_id' => $caixa->id,
                    'usuario_id' => $dadosVenda['usuario_id'],
                    'forma_pagamento_id' => $dadosValidados['forma_pagamento_id'],
                    'tipo_movimentacao' => 'venda',
                    'valor' => $dadosValidados['valor_total_final'],
                    'data_movimentacao' => $dadosVenda['data_venda'],
                    'observacao' => 'Venda #' . $venda->id
                ];
                CaixaMovimentacao::create($dadosCaixa);
                
                $caixa->saldo_atual += $dadosValidados['valor_total_final'];
                $caixa->save();
            }

            DB::commit();

            // Recarregar venda com relacionamentos para verificar se deve gerar NF-e
            $venda->refresh();
            $venda->load('cliente');

            // Gerar NF-e automaticamente se cliente for PJ (obrigatório por lei)
            if ($venda->deveGerarNFeAutomaticamente()) {
                try {
                    // Buscar emitente (pega o primeiro cadastrado)
                    $emitente = Emitente::first();
                    
                    if ($emitente) {
                        $nfeService = app(NFeService::class);
                        $nfeService->gerarNFe($venda->id, $emitente->id, '2'); // Ambiente 2 = Homologação
                        
                        Log::info("NF-e gerada automaticamente para venda #{$venda->id} - Cliente PJ: {$venda->cliente->nome}");
                    } else {
                        Log::warning("Não foi possível gerar NF-e automaticamente para venda #{$venda->id}: Nenhum emitente cadastrado");
                    }
                } catch (\Exception $e) {
                    // Se der erro ao gerar NF-e, não desfaz a venda (já foi finalizada)
                    // Mas registra o erro para correção posterior
                    Log::error("Erro ao gerar NF-e automaticamente para venda #{$venda->id}: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            DB::rollBack(); //se algo falhar ISSO desfaz tudo (venda e etc...)

            return back()->withErrors(['erro_geral' => 'Erro ao finalizar a venda: ' . $e->getMessage()]);
        }

        $mensagem = 'Venda finalizada com sucesso!';
        if ($venda->deveGerarNFeAutomaticamente() && $venda->notaFiscal) {
            $mensagem .= ' NF-e gerada automaticamente.';
        }

        return redirect('/vendas/create')->with('success', $mensagem);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $venda = Venda::with(['cliente', 'user', 'items', 'items.produto', 'notaFiscal', 'usuarioCancelamento'])
                     ->findOrFail($id);

        return view('vendas.show', ['venda' => $venda]);
    }

    /**
     * Cancela uma venda e estorna estoque, movimentações e contas
     */
    public function cancelar(Request $request, $id)
    {
        $dadosValidados = $request->validate([
            'motivo_cancelamento' => 'required|string|max:255'
        ]);

        $venda = Venda::with(['items.produto', 'contasReceber', 'notaFiscal'])->findOrFail($id);

        if (!$venda->podeSerCancelada()) {
            return back()->withErrors(['erro' => 'Esta venda não pode ser cancelada.']);
        }

        // Verificar se tem NF-e autorizada (não pode cancelar se já foi autorizada na SEFAZ)
        if ($venda->notaFiscal && $venda->notaFiscal->status === 'autorizada') {
            return back()->withErrors(['erro' => 'Não é possível cancelar venda com NF-e já autorizada. É necessário cancelar a NF-e primeiro na SEFAZ.']);
        }

        try {
            DB::beginTransaction();

            // 1. Estornar estoque dos produtos
            foreach ($venda->items as $item) {
                $produto = $item->produto;
                $produto->estoque_atual += $item->quantidade;
                $produto->save();
            }

            // 2. Estornar movimentação de caixa
            $caixa = \App\Models\Caixa::where('status', 'aberto')->first();
            if ($caixa) {
                // Buscar movimentação da venda
                $movimentacao = CaixaMovimentacao::where('caixa_id', $caixa->id)
                    ->where('tipo_movimentacao', 'venda')
                    ->where('observacao', 'Venda #' . $venda->id)
                    ->first();

                if ($movimentacao) {
                    // Criar movimentação de estorno
                    CaixaMovimentacao::create([
                        'caixa_id' => $caixa->id,
                        'usuario_id' => Auth::id(),
                        'forma_pagamento_id' => $movimentacao->forma_pagamento_id,
                        'tipo_movimentacao' => 'estorno',
                        'valor' => $movimentacao->valor,
                        'data_movimentacao' => now(),
                        'observacao' => 'Estorno - Venda #' . $venda->id . ' cancelada'
                    ]);

                    // Atualizar saldo do caixa
                    $caixa->saldo_atual -= $movimentacao->valor;
                    $caixa->save();
                }
            }

            // 3. Cancelar contas a receber relacionadas
            foreach ($venda->contasReceber as $conta) {
                if ($conta->status === 'pendente') {
                    $conta->update(['status' => 'cancelada']);
                }
            }

            // 4. Atualizar status da venda
            $venda->update([
                'status' => 'cancelada',
                'motivo_cancelamento' => $dadosValidados['motivo_cancelamento'],
                'usuario_cancelamento_id' => Auth::id(),
                'data_cancelamento' => now()
            ]);

            // 5. Se tiver NF-e em digitação, pode cancelar também
            if ($venda->notaFiscal && $venda->notaFiscal->status === 'digitacao') {
                $venda->notaFiscal->update(['status' => 'cancelada']);
            }

            DB::commit();

            Log::info("Venda #{$venda->id} cancelada por usuário #" . Auth::id() . ". Motivo: " . $dadosValidados['motivo_cancelamento']);

            return redirect()->route('vendas.show', $venda->id)
                ->with('success', 'Venda cancelada com sucesso! Estoque e movimentações foram estornados.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cancelar venda #' . $id . ': ' . $e->getMessage());
            return back()->withErrors(['erro' => 'Erro ao cancelar venda: ' . $e->getMessage()]);
        }
    }
}