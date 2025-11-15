<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\CaixaMovimentacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaixaController extends Controller
{
    public function index()
    {
        $caixas = Caixa::with('movimentacoes')->get();
        return view('caixa.index', ['caixas' => $caixas]);
    }

    public function create()
    {
        return view('caixa.create');
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'descricao' => 'required|string|max:255|unique:caixas,descricao',
            'saldo_atual' => 'nullable|numeric|min:0'
        ]);

        $dadosValidados['status'] = 'fechado';
        $dadosValidados['saldo_atual'] = $dadosValidados['saldo_atual'] ?? 0;

        Caixa::create($dadosValidados);

        return redirect('/caixa');
    }

    public function edit($id)
    {
        $caixa = Caixa::findOrFail($id);
        return view('caixa.edit', ['caixa' => $caixa]);
    }

    public function update(Request $request, $id)
    {
        $caixa = Caixa::findOrFail($id);

        $dadosValidados = $request->validate([
            'descricao' => 'required|string|max:255|unique:caixas,descricao,' . $id,
            'status' => 'required|in:aberto,fechado',
            'saldo_atual' => 'required|numeric|min:0'
        ]);

        $caixa->update($dadosValidados);

        return redirect('/caixa');
    }

    public function abrir($id)
    {
        $caixa = Caixa::findOrFail($id);

        if ($caixa->status === 'aberto') {
            return back()->withErrors(['erro' => 'Caixa já está aberto']);
        }

        $caixa->update(['status' => 'aberto']);

        return redirect('/caixa');
    }

    public function fechar($id)
    {
        $caixa = Caixa::findOrFail($id);

        if ($caixa->status === 'fechado') {
            return back()->withErrors(['erro' => 'Caixa já está fechado']);
        }

        $caixa->update(['status' => 'fechado']);

        return redirect('/caixa');
    }

    public function movimentacoes($id)
    {
        $caixa = Caixa::findOrFail($id);
        
        $movimentacoes = CaixaMovimentacao::where('caixa_id', $id)
            ->with(['usuario', 'formaPagamento'])
            ->orderBy('data_movimentacao', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('caixa.movimentacoes', [
            'caixa' => $caixa,
            'movimentacoes' => $movimentacoes
        ]);
    }

    public function adicionarMovimentacao(Request $request, $id)
    {
        $caixa = Caixa::findOrFail($id);

        if ($caixa->status !== 'aberto') {
            return back()->withErrors(['erro' => 'Caixa deve estar aberto para movimentações']);
        }

        $dadosValidados = $request->validate([
            'tipo_movimentacao' => 'required|in:entrada,saida,suprimento,sangria',
            'valor' => 'required|numeric|min:0.01',
            'forma_pagamento_id' => 'nullable|exists:formas_pagamento,id',
            'observacao' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $movimentacao = CaixaMovimentacao::create([
                'caixa_id' => $id,
                'usuario_id' => Auth::id(),
                'forma_pagamento_id' => $dadosValidados['forma_pagamento_id'] ?? null,
                'tipo_movimentacao' => $dadosValidados['tipo_movimentacao'],
                'valor' => $dadosValidados['valor'],
                'data_movimentacao' => now(),
                'observacao' => $dadosValidados['observacao'] ?? null
            ]);

            if (in_array($dadosValidados['tipo_movimentacao'], ['entrada', 'suprimento'])) {
                $caixa->saldo_atual += $dadosValidados['valor'];
            } else {
                $caixa->saldo_atual -= $dadosValidados['valor'];
            }

            $caixa->save();

            DB::commit();

            return redirect()->route('caixa.movimentacoes', $id)
                ->with('success', 'Movimentação registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erro' => 'Erro ao registrar movimentação: ' . $e->getMessage()]);
        }
    }
}

