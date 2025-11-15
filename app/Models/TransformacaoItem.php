<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransformacaoItem extends Model
{
    use HasFactory;

    protected $table = 'transformacao_itens';

    protected $fillable = [
        'transformacao_id',
        'produto_destino_id',
        'quantidade',
        'tipo'
    ];

    public function transformacao()
    {
        return $this->belongsTo(Transformacao::class, 'transformacao_id');
    }

    public function produtoDestino()
    {
        return $this->belongsTo(Produto::class, 'produto_destino_id');
    }
}

