<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemNfe extends Model
{
    use HasFactory;

    protected $table = 'item_nfe';

    protected $fillable = [
        'nota_fiscal_id',
        'produto_id',
        'ordem',
        'codigo_item',
        'descricao',
        'ncm',
        'cest',
        'cfop',
        'unidade',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'desconto_item',
        'origem',
        'cst_icms',
        'aliquota_icms',
        'valor_icms',
        'cst_pis',
        'aliquota_pis',
        'valor_pis',
        'cst_cofins',
        'aliquota_cofins',
        'valor_cofins',
        'valor_ipi'
    ];

    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'nota_fiscal_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

