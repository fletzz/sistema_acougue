<?php

namespace App\Http\Controllers;

use App\Models\EntradaMercadoria;
use App\Models\EntradaMercadoriaItem;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntradaMercadoriaController extends Controller
{
    public function index()
    {
        $entradas = EntradaMercadoria::with(['fornecedor', 'usuario', 'itens.produto'])
            ->latest()
            ->get();

        return view('entrada_mercadoria.index', ['entradas' => $entradas]);
    }

    public function create()
    {
        $fornecedores = Fornecedor::all();
        $produtos = Produto::all();
        $categorias = \App\Models\Categoria::all();

        return view('entrada_mercadoria.create', [
            'fornecedores' => $fornecedores,
            'produtos' => $produtos,
            'categorias' => $categorias
        ]);
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'fornecedor_id' => 'nullable|exists:fornecedores,id',
            'data_entrada' => 'required|date',
            'observacao' => 'nullable|string',
            'itens' => 'required|string'
        ]);

        $itemsArray = json_decode($dadosValidados['itens'], true);

        try {
            DB::beginTransaction();

            $entrada = EntradaMercadoria::create([
                'fornecedor_id' => $dadosValidados['fornecedor_id'] ?? null,
                'usuario_id' => Auth::id(),
                'data_entrada' => $dadosValidados['data_entrada'],
                'observacao' => $dadosValidados['observacao'] ?? null
            ]);

            foreach ($itemsArray as $item) {
                $produtoId = $item['produto_id'];

                if (isset($item['novo_produto']) && $item['novo_produto'] === true) {
                    $novoProduto = Produto::create([
                        'nome' => $item['nome_produto'],
                        'categoria_id' => $item['categoria_id'],
                        'preco_venda' => 0,
                        'unidade_medida' => $item['unidade_medida'],
                        'codigo_barras' => null,
                        'estoque_atual' => 0,
                        'estoque_minimo' => 0
                    ]);
                    $produtoId = $novoProduto->id;
                }

                EntradaMercadoriaItem::create([
                    'entrada_mercadoria_id' => $entrada->id,
                    'produto_id' => $produtoId,
                    'quantidade' => $item['quantidade'],
                    'preco_custo' => $item['preco_custo'] ?? null
                ]);

                $produto = Produto::findOrFail($produtoId);
                $produto->estoque_atual += $item['quantidade'];
                $produto->save();
            }

            DB::commit();

            return redirect()->route('entrada_mercadoria.index')
                ->with('success', 'Entrada de mercadoria registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erro' => 'Erro ao registrar entrada: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $entrada = EntradaMercadoria::with(['fornecedor', 'usuario', 'itens.produto'])
            ->findOrFail($id);

        return view('entrada_mercadoria.show', ['entrada' => $entrada]);
    }
}

