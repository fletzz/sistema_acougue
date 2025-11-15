<?php

namespace App\Http\Controllers;

use App\Models\Emitente;
use Illuminate\Http\Request;

class EmitenteController extends Controller
{
    public function index()
    {
        $emitentes = Emitente::all();
        return view('emitente.index', ['emitentes' => $emitentes]);
    }

    public function create()
    {
        return view('emitente.create');
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'cnpj' => 'required|string|size:14|unique:emitente,cnpj',
            'razao_social' => 'required|string|max:120',
            'nome_fantasia' => 'required|string|max:60',
            'inscricao_estadual' => 'nullable|string|max:14',
            'inscricao_municipal' => 'nullable|string|max:15',
            'crt' => 'required|string|size:1',
            'logradouro' => 'required|string|max:100',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:60',
            'bairro' => 'required|string|max:60',
            'codigo_municipio' => 'required|integer',
            'municipio' => 'required|string|max:60',
            'uf' => 'required|string|size:2',
            'cep' => 'required|string|size:8',
            'telefone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:120'
        ]);

        Emitente::create($dadosValidados);

        return redirect('/emitente');
    }

    public function edit($id)
    {
        $emitente = Emitente::findOrFail($id);
        return view('emitente.edit', ['emitente' => $emitente]);
    }

    public function update(Request $request, $id)
    {
        $emitente = Emitente::findOrFail($id);

        $dadosValidados = $request->validate([
            'cnpj' => 'required|string|size:14|unique:emitente,cnpj,' . $id,
            'razao_social' => 'required|string|max:120',
            'nome_fantasia' => 'required|string|max:60',
            'inscricao_estadual' => 'nullable|string|max:14',
            'inscricao_municipal' => 'nullable|string|max:15',
            'crt' => 'required|string|size:1',
            'logradouro' => 'required|string|max:100',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:60',
            'bairro' => 'required|string|max:60',
            'codigo_municipio' => 'required|integer',
            'municipio' => 'required|string|max:60',
            'uf' => 'required|string|size:2',
            'cep' => 'required|string|size:8',
            'telefone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:120'
        ]);

        $emitente->update($dadosValidados);

        return redirect('/emitente');
    }

    public function destroy($id)
    {
        Emitente::destroy($id);
        return redirect('/emitente');
    }
}

