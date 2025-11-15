<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transformacao extends Model
{
    use HasFactory;

    protected $table = 'transformacoes';

    protected $fillable = [
        'produto_origem_id',
        'quantidade_origem',
        'usuario_id',
        'data_transformacao',
        'observacao'
    ];

    protected $casts = [
        'data_transformacao' => 'datetime'
    ];

    public function produtoOrigem()
    {
        return $this->belongsTo(Produto::class, 'produto_origem_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function itens()
    {
        return $this->hasMany(TransformacaoItem::class, 'transformacao_id');
    }
}

