<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    use HasFactory;

    protected $table = 'nota_fiscal';

    protected $fillable = [
        'venda_id',
        'emitente_id',
        'destinatario_id',
        'numero',
        'serie',
        'modelo',
        'tipo_operacao',
        'finalidade',
        'natureza_operacao',
        'ambiente',
        'tipo_emissao',
        'data_emissao',
        'data_saida_entrada',
        'chave_acesso',
        'protocolo_autorizacao',
        'status',
        'valor_total_produtos',
        'valor_frete',
        'valor_seguro',
        'valor_desconto',
        'valor_impostos',
        'valor_total_nfe',
        'informacoes_adicionais',
        'versao_leiaute'
    ];

    protected $casts = [
        'data_emissao' => 'datetime',
        'data_saida_entrada' => 'datetime'
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function emitente()
    {
        return $this->belongsTo(Emitente::class);
    }

    public function destinatario()
    {
        return $this->belongsTo(Cliente::class, 'destinatario_id');
    }

    public function itens()
    {
        return $this->hasMany(ItemNfe::class, 'nota_fiscal_id');
    }

    public function pagamentos()
    {
        return $this->hasMany(PagamentoNfe::class, 'nota_fiscal_id');
    }

    public function transporte()
    {
        return $this->hasOne(Transporte::class, 'nota_fiscal_id');
    }
}

