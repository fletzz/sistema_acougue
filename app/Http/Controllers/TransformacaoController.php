<?php

namespace App\Http\Controllers;

use App\Models\Transformacao;
use App\Models\TransformacaoItem;
use App\Models\Produto;
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
}

