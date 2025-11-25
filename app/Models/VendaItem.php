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

    /**
     * Calcula o lucro do item da venda
     * @return float|null
     */
    public function getLucroAttribute()
    {
        $produto = $this->produto;
        if (!$produto || !$produto->preco_custo) {
            return null;
        }

        $lucroUnitario = $this->preco_unitario - $produto->preco_custo;
        return $lucroUnitario * $this->quantidade;
    }

    /**
     * Calcula a margem de lucro do item
     * @return float|null
     */
    public function getMargemLucroAttribute()
    {
        $produto = $this->produto;
        if (!$produto || !$produto->preco_custo || $produto->preco_custo <= 0) {
            return null;
        }

        $lucroUnitario = $this->preco_unitario - $produto->preco_custo;
        if ($lucroUnitario <= 0) {
            return 0;
        }

        return ($lucroUnitario / $produto->preco_custo) * 100;
    }
}
