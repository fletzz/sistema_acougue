<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaMovimentacao extends Model
{
    use HasFactory;

    protected $table = 'caixa_movimentacoes';

    protected $fillable = [
        'caixa_id',
        'usuario_id',
        'forma_pagamento_id',
        'tipo_movimentacao',
        'valor',
        'data_movimentacao',
        'observacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'datetime'
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }
}