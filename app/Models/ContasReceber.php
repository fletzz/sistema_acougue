<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContasReceber extends Model
{
    use HasFactory;

    protected $fillable = ['venda_id','cliente_id','forma_pagamento_id','valor_pago','data_pagamento'];

    protected $table = 'contas_receber';
}
