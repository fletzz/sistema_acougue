<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoNfe extends Model
{
    use HasFactory;

    protected $table = 'pagamento_nfe';

    protected $fillable = [
        'nota_fiscal_id',
        'forma_pagamento_id',
        'valor_pagamento',
        'troco',
        'tipo_integracao',
        'cnpj_credenciadora',
        'bandeira',
        'numero_autorizacao'
    ];

    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'nota_fiscal_id');
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }
}

