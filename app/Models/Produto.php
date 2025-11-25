<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'nome',
        'categoria_id',
        'preco_venda',
        'preco_custo',
        'unidade_medida',
        'codigo_barras',
        'ncm_codigo',
        'origem_mercadoria',
        'cest',
        'cfop',
        'csosn',
        'cst_icms',
        'aliquota_icms',
        'aliquota_pis',
        'aliquota_cofins',
        'data_validade',
        'lote'
    ];

    protected $table = 'produtos';

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function vendaItems() {
        return $this->hasMany(VendaItem::class);
    }

    protected $casts = [
        'data_validade' => 'date'
    ];

    /**
     * Calcula a margem de lucro em percentual
     * @return float|null
     */
    public function getMargemLucroAttribute()
    {
        if (!$this->preco_custo || $this->preco_custo <= 0) {
            return null;
        }

        if ($this->preco_venda <= $this->preco_custo) {
            return 0;
        }

        $lucro = $this->preco_venda - $this->preco_custo;
        return ($lucro / $this->preco_custo) * 100;
    }

    /**
     * Calcula o valor do lucro
     * @return float|null
     */
    public function getLucroAttribute()
    {
        if (!$this->preco_custo) {
            return null;
        }

        return $this->preco_venda - $this->preco_custo;
    }

    /**
     * Verifica se o produto está próximo do vencimento (7 dias)
     * @return bool
     */
    public function isProximoVencimento()
    {
        if (!$this->data_validade) {
            return false;
        }

        $diasRestantes = now()->diffInDays($this->data_validade, false);
        return $diasRestantes >= 0 && $diasRestantes <= 7;
    }

    /**
     * Verifica se o produto está vencido
     * @return bool
     */
    public function isVencido()
    {
        if (!$this->data_validade) {
            return false;
        }

        return now()->isAfter($this->data_validade);
    }
}
