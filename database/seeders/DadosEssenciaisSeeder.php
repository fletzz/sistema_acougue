<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caixa; // <-- Importa o Model

class DadosEssenciaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. CRIA O CAIXA PRINCIPAL (O que faltava)
        Caixa::create([
            'id' => 1, // Força o ID a ser 1
            'descricao' => 'Caixa Principal',
            'status' => 'fechado',
            'saldo_atual' => 0
        ]);
        // (Aqui poderíamos adicionar Formas de Pagamento, etc.)
    }
}