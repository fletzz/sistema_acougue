<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaMovimentacao extends Model
{
    use HasFactory;

    protected $table = 'caixa_movimentacoes';

    protected $fillable = ['caixa_id', 'usuario_id','forma_pagamento_id','tipo_movimentacao','valor','data_movimentacao'];
}