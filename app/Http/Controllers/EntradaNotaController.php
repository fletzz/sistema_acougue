<?php

namespace App\Http\Controllers;

use App\Models\EntradaNota;
use App\Models\EntradaNotaItem;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EntradaNotaController extends Controller
{
    public function index()
    {
        $entradas = EntradaNota::with(['fornecedor', 'itens.produto'])
            ->latest()
            ->get();

        return view('entrada_nota.index', ['entradas' => $entradas]);
    }

    public function create()
    {
        $fornecedores = Fornecedor::all();
        $produtos = Produto::all();

        return view('entrada_nota.create', [
            'fornecedores' => $fornecedores,
            'produtos' => $produtos
        ]);
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'numero_nota' => 'required|string',
            'serie' => 'required|string',
            'chave_acesso' => 'required|string|size:44|unique:entradas_notas,chave_acesso',
            'data_emissao' => 'required|date',
            'data_entrada' => 'required|date',
            'valor_total_nota' => 'required|numeric|min:0',
            'items' => 'required|string'
        ]);

        $itemsArray = json_decode($dadosValidados['items'], true);

        try {
            DB::beginTransaction();

            $entradaNota = EntradaNota::create([
                'fornecedor_id' => $dadosValidados['fornecedor_id'],
                'numero_nota' => $dadosValidados['numero_nota'],
                'serie' => $dadosValidados['serie'],
                'chave_acesso' => $dadosValidados['chave_acesso'],
                'data_emissao' => $dadosValidados['data_emissao'],
                'data_entrada' => $dadosValidados['data_entrada'],
                'valor_total_nota' => $dadosValidados['valor_total_nota']
            ]);

            foreach ($itemsArray as $item) {
                EntradaNotaItem::create([
                    'entrada_nota_id' => $entradaNota->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'preco_custo_unitario' => $item['preco_custo_unitario'],
                    'cfop' => $item['cfop'] ?? null,
                    'cst_icms' => $item['cst_icms'] ?? null,
                    'base_calculo_icms' => $item['base_calculo_icms'] ?? 0,
                    'aliquota_icms' => $item['aliquota_icms'] ?? 0,
                    'valor_icms' => $item['valor_icms'] ?? 0,
                    'cst_pis' => $item['cst_pis'] ?? null,
                    'base_calculo_pis' => $item['base_calculo_pis'] ?? 0,
                    'aliquota_pis' => $item['aliquota_pis'] ?? 0,
                    'valor_pis' => $item['valor_pis'] ?? 0,
                    'cst_cofins' => $item['cst_cofins'] ?? null,
                    'base_calculo_cofins' => $item['base_calculo_cofins'] ?? 0,
                    'aliquota_cofins' => $item['aliquota_cofins'] ?? 0,
                    'valor_cofins' => $item['valor_cofins'] ?? 0,
                    'valor_ipi' => $item['valor_ipi'] ?? 0
                ]);

                $produto = Produto::findOrFail($item['produto_id']);
                $produto->estoque_atual += $item['quantidade'];
                $produto->save();
            }

            DB::commit();

            return redirect()->route('entrada_nota.index')
                ->with('success', 'Entrada de nota registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erro' => 'Erro ao registrar entrada: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $entrada = EntradaNota::with(['fornecedor', 'itens.produto'])
            ->findOrFail($id);

        return view('entrada_nota.show', ['entrada' => $entrada]);
    }
}

