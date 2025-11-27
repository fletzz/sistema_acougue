<?php

namespace App\Http\Controllers;

use App\Models\Transformacao;
use App\Models\TransformacaoItem;
use App\Models\Produto;
use App\Models\EntradaMercadoria;
use App\Models\EntradaMercadoriaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransformacaoController extends Controller
{
    public function index()
    {
        $transformacoes = Transformacao::with(['produtoOrigem', 'usuario', 'itens.produtoDestino'])
            ->latest()
            ->get();

        return view('transformacao.index', ['transformacoes' => $transformacoes]);
    }

    public function create()
    {
        $produtos = Produto::where('estoque_atual', '>', 0)->get();
        $categorias = \App\Models\Categoria::all();
        return view('transformacao.create', [
            'produtos' => $produtos,
            'categorias' => $categorias
        ]);
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'produto_origem_id' => 'required|exists:produtos,id',
            'quantidade_origem' => 'required|numeric|min:0.001',
            'observacao' => 'nullable|string',
            'itens' => 'required|string'
        ]);

        $itemsArray = json_decode($dadosValidados['itens'], true);

        try {
            DB::beginTransaction();

            $produtoOrigem = Produto::findOrFail($dadosValidados['produto_origem_id']);

            if ($produtoOrigem->estoque_atual < $dadosValidados['quantidade_origem']) {
                throw new \Exception('Estoque insuficiente. Disponível: ' . $produtoOrigem->estoque_atual);
            }

            $transformacao = Transformacao::create([
                'produto_origem_id' => $dadosValidados['produto_origem_id'],
                'quantidade_origem' => $dadosValidados['quantidade_origem'],
                'usuario_id' => Auth::id(),
                'data_transformacao' => now(),
                'observacao' => $dadosValidados['observacao'] ?? null
            ]);

            $produtoOrigem->estoque_atual -= $dadosValidados['quantidade_origem'];
            $produtoOrigem->save();

            foreach ($itemsArray as $item) {
                $produtoDestinoId = $item['produto_destino_id'];

                if (isset($item['novo_produto']) && $item['novo_produto'] === true) {
                    $novoProduto = Produto::create([
                        'nome' => $item['nome_produto'],
                        'categoria_id' => $item['categoria_id'],
                        'preco_venda' => $item['preco_venda'] ?? 0,
                        'unidade_medida' => $item['unidade_medida'],
                        'codigo_barras' => $item['codigo_barras'] ?? null,
                        'estoque_atual' => 0,
                        'estoque_minimo' => 0
                    ]);
                    $produtoDestinoId = $novoProduto->id;
                }

                TransformacaoItem::create([
                    'transformacao_id' => $transformacao->id,
                    'produto_destino_id' => $produtoDestinoId,
                    'quantidade' => $item['quantidade'],
                    'tipo' => $item['tipo'] ?? 'corte'
                ]);

                $produtoDestino = Produto::findOrFail($produtoDestinoId);
                $produtoDestino->estoque_atual += $item['quantidade'];
                $produtoDestino->save();
            }

            DB::commit();

            return redirect()->route('transformacao.index')
                ->with('success', 'Transformação registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erro' => 'Erro ao registrar transformação: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $transformacao = Transformacao::with([
            'produtoOrigem',
            'usuario',
            'itens.produtoDestino'
        ])->findOrFail($id);

        return view('transformacao.show', ['transformacao' => $transformacao]);
    }

    /**
     * Mostra o formulário para criar uma nova desossa a partir de uma entrada
     */
    public function createFromEntrada(EntradaMercadoria $entrada, EntradaMercadoriaItem $item)
    {
        // Carregar os relacionamentos necessários
        $entrada->load('fornecedor');
        $item->load('produto');

        // Verificar se o item pertence à entrada
        if ($item->entrada_mercadoria_id !== $entrada->id) {
            abort(404);
        }

        // Verificar se já existe uma transformação para este item
        if ($item->transformacao) {
            return redirect()->route('transformacao.show', $item->transformacao->id)
                ->with('info', 'Este item já foi processado em uma desossa.');
        }

        // Buscar produtos que podem ser resultado de desossa (cortes)
        $cortes = Produto::where('nome', 'like', '%corte%')
            ->orWhere('nome', 'like', '%picanha%')
            ->orWhere('nome', 'like', '%alcatra%')
            ->orWhere('nome', 'like', '%maminha%')
            ->orWhere('nome', 'like', '%costela%')
            ->orWhere('nome', 'like', '%osso%')
            ->orderBy('nome')
            ->get();

        return view('transformacao.nova_desossa', [
            'entrada' => $entrada,
            'itemEntrada' => $item,
            'cortes' => $cortes
        ]);
    }

    /**
     * Processa o formulário de desossa
     */
    public function storeFromEntrada(Request $request)
    {
        $request->validate([
            'entrada_id' => 'required|exists:entradas_mercadoria,id',
            'item_entrada_id' => 'required|exists:entrada_mercadoria_itens,id',
            'produto_id' => 'required|array',
            'produto_id.*' => 'exists:produtos,id',
            'peso' => 'required|array',
            'peso.*' => 'required|numeric|min:0.001',
        ]);

        $entrada = EntradaMercadoria::findOrFail($request->entrada_id);
        $itemEntrada = EntradaMercadoriaItem::findOrFail($request->item_entrada_id);
        
        // Verificar se o item pertence à entrada
        if ($itemEntrada->entrada_mercadoria_id !== $entrada->id) {
            abort(404);
        }

        // Verificar se já existe uma transformação para este item
        if ($itemEntrada->transformacao) {
            return redirect()->route('transformacao.show', $itemEntrada->transformacao->id)
                ->with('info', 'Este item já foi processado em uma desossa.');
        }

        // Calcular o peso total dos cortes
        $pesoTotalCortes = array_sum($request->peso);
        $pesoOriginal = $itemEntrada->quantidade;
        $quebra = $pesoOriginal - $pesoTotalCortes;
        $percentualQuebra = ($quebra / $pesoOriginal) * 100;

        DB::beginTransaction();
        
        try {
            // Criar a transformação
            $transformacao = Transformacao::create([
                'produto_origem_id' => $itemEntrada->produto_id,
                'quantidade_origem' => $pesoOriginal,
                'usuario_id' => Auth::id(),
                'data_transformacao' => now(),
                'observacao' => "Desossa do item #{$itemEntrada->id} da entrada #{$entrada->id}",
                'entrada_mercadoria_item_id' => $itemEntrada->id
            ]);

            // Processar cada corte
            foreach ($request->produto_id as $index => $produtoId) {
                $peso = $request->peso[$index];
                
                // Criar item da transformação
                $transformacao->itens()->create([
                    'produto_destino_id' => $produtoId,
                    'quantidade' => $peso,
                    'tipo' => 'corte'
                ]);

                // Atualizar estoque do produto de destino
                $produto = Produto::findOrFail($produtoId);
                $produto->increment('estoque_atual', $peso);
            }

            // Atualizar o item da entrada para marcar como processado
            $itemEntrada->update([
                'processado' => true,
                'quantidade_processada' => $pesoTotalCortes
            ]);

            // Atualizar o produto de origem (reduzir estoque)
            $produtoOrigem = $itemEntrada->produto;
            $produtoOrigem->decrement('estoque_atual', $pesoOriginal);

            DB::commit();

            return redirect()->route('transformacao.show', $transformacao->id)
                ->with('success', 'Desossa registrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao registrar desossa: ' . $e->getMessage()]);
        }
    }
}

