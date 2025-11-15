<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaMercadoria extends Model
{
    use HasFactory;

    protected $table = 'entradas_mercadoria';

    protected $fillable = [
        'fornecedor_id',
        'usuario_id',
        'data_entrada',
        'observacao'
    ];

    protected $casts = [
        'data_entrada' => 'datetime'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function itens()
    {
        return $this->hasMany(EntradaMercadoriaItem::class, 'entrada_mercadoria_id');
    }
}

