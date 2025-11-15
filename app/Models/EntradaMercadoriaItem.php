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
        'preco_custo'
    ];

    public function entradaMercadoria()
    {
        return $this->belongsTo(EntradaMercadoria::class, 'entrada_mercadoria_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

