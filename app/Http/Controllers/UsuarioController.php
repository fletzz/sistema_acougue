<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::all();

        return view('usuarios.index', ['usuarios' => $usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('usuarios.create');
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
            'nome' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
            'nivel_acesso' => ['nullable', 'string', Rule::in(['admin', 'caixa'])]
        ]);

        $dadosValidados['password'] = Hash::make($dadosValidados['password']);

        Usuario::create($dadosValidados);
        return redirect('/usuarios');
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
        $usuario = Usuario::findOrFail($id);

        return view('usuarios.edit', ['usuario' => $usuario]);
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
        $usuario = Usuario::findOrFail($id);

        $dadosValidados = $request->validate([
            'nome' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:usuarios,login,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'nivel_acesso' => ['nullable', 'string', Rule::in(['admin', 'caixa'])]
        ]);

        if (!empty($dadosValidados['password'])) {
            $dadosValidados['password'] = Hash::make($dadosValidados['password']);
        } else {
            unset($dadosValidados['password']);
        }
        $usuario->update($dadosValidados); 

        return redirect('/usuarios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Usuario::destroy($id);
        return redirect('/usuarios');
    }
}
