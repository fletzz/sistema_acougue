<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaMercadoriaItem extends Model
{
    use HasFactory;

    protected $table = 'entrada_mercadoria_itens';

    protected $fillable = [
        'entrada_mercadoria_id',
        'produto_id',
        'quantidade',
        'preco_custo',
        'processado',
        'quantidade_processada'
    ];

    protected $casts = [
        'processado' => 'boolean',
        'quantidade' => 'decimal:3',
        'quantidade_processada' => 'decimal:3',
        'preco_custo' => 'decimal:4'
    ];

    public function entradaMercadoria()
    {
        return $this->belongsTo(EntradaMercadoria::class, 'entrada_mercadoria_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Relacionamento com a transformação (desossa) gerada a partir deste item
     */
    public function transformacao()
    {
        return $this->hasOne(\App\Models\Transformacao::class, 'entrada_mercadoria_item_id');
    }
}

