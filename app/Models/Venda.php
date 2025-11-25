<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id','usuario_id','data_venda','valor_total_produtos','valor_desconto','valor_total_final','status','observacao'];

    protected $table = 'vendas';

    protected $casts = ['data_venda' => 'datetime',];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function items() {
        return $this->hasMany(VendaItem::class);
    }

    public function notaFiscal() {
        return $this->hasOne(NotaFiscal::class);
    }

    public function contasReceber() {
        return $this->hasMany(ContasReceber::class);
    }

    /**
     * Verifica se a venda deve gerar NF-e automaticamente
     * Regras:
     * - Se cliente é PJ (CNPJ): SEMPRE gerar (obrigatório por lei)
     * - Se cliente é PF (CPF) e valor > R$ 100: Sugerir gerar
     * - Se consumidor final: Não gerar automaticamente
     * 
     * @return bool
     */
    public function deveGerarNFeAutomaticamente()
    {
        // Se não tem cliente, é consumidor final - não gerar automaticamente
        if (!$this->cliente) {
            return false;
        }

        // Se cliente é PJ (CNPJ) → SEMPRE gerar (obrigatório por lei)
        if ($this->cliente->isPessoaJuridica()) {
            return true;
        }

        // Para PF ou outros casos, não gerar automaticamente
        // (pode ser gerado manualmente depois se necessário)
        return false;
    }
}
