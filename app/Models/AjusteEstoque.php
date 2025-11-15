<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjusteEstoque extends Model
{
    use HasFactory;

    protected $table = 'ajustes_estoque';

    protected $fillable = [
        'produto_id',
        'usuario_id',
        'quantidade',
        'motivo',
        'data_ajuste'
    ];

    protected $casts = [
        'data_ajuste' => 'datetime'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

