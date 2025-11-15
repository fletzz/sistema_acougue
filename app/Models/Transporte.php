<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    use HasFactory;

    protected $table = 'transporte';

    protected $fillable = [
        'nota_fiscal_id',
        'modalidade_frete',
        'cnpj_transportador',
        'nome_transportador',
        'ie_transportador',
        'endereco_transportador',
        'municipio',
        'uf',
        'placa_veiculo',
        'uf_veiculo',
        'especie',
        'numeracao',
        'quantidade_volumes',
        'peso_bruto',
        'peso_liquido'
    ];

    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'nota_fiscal_id');
    }
}

