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


class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();
        $formaPagamentos = FormaPagamento::all();

        return view ('vendas.create' , [
            'clientes' => $clientes,
            'produtos' => $produtos,
            'formaPagamentos' => $formaPagamentos
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
                'forma_pagamento_id' => $dadosValidados['forma_pagamento_id'],
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); //se algo falhar ISSO desfaz tudo (venda e etc...)

            return back()->withErrors(['erro_geral' => 'Erro ao finalizar a venda: ' . $e->getMessage()]);
        }

        return redirect('/vendas/create')->with('success', 'Venda finalizada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }
}