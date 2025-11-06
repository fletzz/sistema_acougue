<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id','usuario_id','data_venda','valor_total_produtos','valor_desconto','valor_total_final','status','observacao'];

    protected $table = 'vendas';

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function items() {
        return $this->hasMany(VendaItem::class);
    }
}
