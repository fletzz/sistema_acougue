<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Carrega todos os produtos para o PDV (ajuste se quiser paginação depois)
        $produtos = Produto::orderBy('nome')->get();

        return view('checkout', [
            'user'     => $user,
            'produtos' => $produtos,
        ]);
    }
}
