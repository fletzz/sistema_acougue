<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaNota extends Model
{
    use HasFactory;

    protected $table = 'entradas_notas';

    protected $fillable = [
        'fornecedor_id',
        'numero_nota',
        'serie',
        'chave_acesso',
        'data_emissao',
        'data_entrada',
        'valor_total_nota'
    ];

    protected $casts = [
        'data_emissao' => 'datetime',
        'data_entrada' => 'datetime'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function itens()
    {
        return $this->hasMany(EntradaNotaItem::class, 'entrada_nota_id');
    }
}

