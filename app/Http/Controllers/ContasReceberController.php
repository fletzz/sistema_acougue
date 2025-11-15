<?php

namespace App\Http\Controllers;

use App\Models\ContasReceber;
use App\Models\Venda;
use App\Models\Cliente;
use App\Models\FormaPagamento;
use Illuminate\Http\Request;

class ContasReceberController extends Controller
{
    public function index()
    {
        $contasReceber = ContasReceber::with(['venda', 'cliente', 'formaPagamento'])
            ->latest()
            ->get();

        return view('contas_receber.index', ['contasReceber' => $contasReceber]);
    }

    public function show($id)
    {
        $contaReceber = ContasReceber::with(['venda.items.produto', 'cliente', 'formaPagamento'])
            ->findOrFail($id);

        return view('contas_receber.show', ['contaReceber' => $contaReceber]);
    }

    public function receber($id)
    {
        $contaReceber = ContasReceber::findOrFail($id);

        if ($contaReceber->status === 'recebido') {
            return back()->withErrors(['erro' => 'Conta já foi recebida']);
        }

        $contaReceber->update([
            'status' => 'recebido',
            'data_pagamento' => now()
        ]);

        return redirect()->route('contas_receber.index')
            ->with('success', 'Conta marcada como recebida!');
    }
}

