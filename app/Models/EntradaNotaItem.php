<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaNotaItem extends Model
{
    use HasFactory;

    protected $table = 'entradas_notas_items';

    protected $fillable = [
        'entrada_nota_id',
        'produto_id',
        'quantidade',
        'preco_custo_unitario',
        'cfop',
        'cst_icms',
        'base_calculo_icms',
        'aliquota_icms',
        'valor_icms',
        'cst_pis',
        'base_calculo_pis',
        'aliquota_pis',
        'valor_pis',
        'cst_cofins',
        'base_calculo_cofins',
        'aliquota_cofins',
        'valor_cofins',
        'valor_ipi'
    ];

    public function entradaNota()
    {
        return $this->belongsTo(EntradaNota::class, 'entrada_nota_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

