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

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($itemsArray) || empty($itemsArray)) {
            return back()->withErrors(['erro' => 'Erro ao processar os itens da entrada. Por favor, tente novamente.']);
        }

        try {
            DB::beginTransaction();

            $entrada = EntradaMercadoria::create([
                'fornecedor_id' => $dadosValidados['fornecedor_id'] ?? null,
                'usuario_id' => Auth::id(),
                'data_entrada' => $dadosValidados['data_entrada'],
                'observacao' => $dadosValidados['observacao'] ?? null
            ]);

            foreach ($itemsArray as $item) {
                $produtoId = null;

                if (isset($item['novo_produto']) && $item['novo_produto'] === true) {
                    // Validação dos campos obrigatórios para novo produto
                    if (empty($item['nome_produto']) || empty($item['categoria_id']) || empty($item['unidade_medida'])) {
                        throw new \Exception('Campos obrigatórios faltando para novo produto: nome, categoria e unidade de medida.');
                    }

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
                } else {
                    if (empty($item['produto_id'])) {
                        throw new \Exception('Produto ID é obrigatório para produtos existentes.');
                    }
                    $produtoId = $item['produto_id'];
                }

                if (empty($item['quantidade']) || $item['quantidade'] <= 0) {
                    throw new \Exception('Quantidade deve ser maior que zero.');
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

            // --- LÓGICA DE DECISÃO DE REDIRECIONAMENTO ---

            // Se o usuário clicou em "Confirmar e Iniciar Desossa"
            if ($request->input('action') === 'desossa') {
                // Pega o primeiro item da nota recém-criada para iniciar a desossa
                $primeiroItem = $entrada->itens()->first();

                if ($primeiroItem) {
                    // Redireciona para a rota de criação de transformação vinculada
                    return redirect()->route('desossa.create', [
                        'entrada' => $entrada->id,
                        'item' => $primeiroItem->id
                    ])->with('success', 'Entrada registrada! Inicie a desossa da peça abaixo.');
                }
            }

            // Fluxo Padrão: Apenas volta para a lista de entradas
            return redirect()->route('entrada_mercadoria.index')
                             ->with('success', 'Entrada de mercadoria registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erro' => 'Erro ao registrar entrada: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $entrada = EntradaMercadoria::with(['fornecedor', 'usuario', 'itens.produto', 'itens.transformacao'])
            ->findOrFail($id);

        return view('entrada_mercadoria.show', ['entrada' => $entrada]);
    }
}

