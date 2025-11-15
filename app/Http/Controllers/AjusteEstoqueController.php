<?php

namespace App\Http\Controllers;

use App\Models\AjusteEstoque;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AjusteEstoqueController extends Controller
{
    public function index()
    {
        $ajustes = AjusteEstoque::with(['produto', 'usuario'])
            ->latest()
            ->get();

        return view('ajuste_estoque.index', ['ajustes' => $ajustes]);
    }

    public function create()
    {
        $produtos = Produto::all();
        return view('ajuste_estoque.create', ['produtos' => $produtos]);
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|numeric',
            'motivo' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $produto = Produto::findOrFail($dadosValidados['produto_id']);

            AjusteEstoque::create([
                'produto_id' => $dadosValidados['produto_id'],
                'usuario_id' => Auth::id(),
                'quantidade' => $dadosValidados['quantidade'],
                'motivo' => $dadosValidados['motivo'],
                'data_ajuste' => now()
            ]);

            $produto->estoque_atual += $dadosValidados['quantidade'];
            $produto->save();

            DB::commit();

            return redirect()->route('ajuste_estoque.index')
                ->with('success', 'Ajuste de estoque registrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erro' => 'Erro ao registrar ajuste: ' . $e->getMessage()]);
        }
    }
}

