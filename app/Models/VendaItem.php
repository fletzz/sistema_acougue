<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaItem extends Model
{
    use HasFactory;

    protected $fillable = ['venda_id','produto_id','quantidade','preco_unitario'];

    protected $table = 'venda_items';

    public function venda() {
        return $this->belongsTo(Venda::class);
    }

    public function produto() {
        return $this->belongsTo(Produto::class);
    }
}
