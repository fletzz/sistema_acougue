<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContasReceber extends Model
{
    use HasFactory;

    protected $table = 'contas_receber';

    protected $fillable = [
        'venda_id',
        'cliente_id',
        'forma_pagamento_id',
        'valor_pago',
        'data_pagamento',
        'status'
    ];

    protected $casts = [
        'data_pagamento' => 'datetime'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }
}
