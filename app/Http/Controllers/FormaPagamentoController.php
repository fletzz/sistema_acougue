<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormaPagamento;

class FormaPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $formaPagamentos = FormaPagamento::all();

        return view('formaPagamentos.index', ['formaPagamentos' => $formaPagamentos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('formaPagamentos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $dadosValidados = $request->validate([
            'descricao' => 'required|string|unique:formas_pagamento',
            'tipo' => 'nullable|string'
        ]);

        FormaPagamento::create($dadosValidados);
        return redirect('/forma_pagamentos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formaPagamento = FormaPagamento::findOrFail($id);

        return view('formaPagamentos.edit', ['formaPagamento' => $formaPagamento]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $FormaPagamento = FormaPagamento::findOrFail($id);

        $dadosValidados = $request->validate([
            'descricao' => 'required|string|unique:formas_pagamento,descricao,' . $id,
            'tipo' => 'nullable|string'
        ]);

        $FormaPagamento->update($dadosValidados); 
        return redirect('/forma_pagamentos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FormaPagamento::destroy($id);
        return redirect('/forma_pagamentos');
    }
}
