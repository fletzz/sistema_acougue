<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'data_venda',
        'valor_total_produtos',
        'valor_desconto',
        'valor_total_final',
        'status',
        'observacao',
        'motivo_cancelamento',
        'usuario_cancelamento_id',
        'data_cancelamento'
    ];

    protected $table = 'vendas';

    protected $casts = [
        'data_venda' => 'datetime',
        'data_cancelamento' => 'datetime'
    ];

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

    public function usuarioCancelamento() {
        return $this->belongsTo(User::class, 'usuario_cancelamento_id');
    }

    public function isCancelada() {
        return $this->status === 'cancelada';
    }

    public function podeSerCancelada() {
        // Não pode cancelar se já foi cancelada
        if ($this->isCancelada()) {
            return false;
        }
        
        // Pode cancelar se está finalizada
        return $this->status === 'finalizada';
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

    /**
     * Calcula o lucro total da venda
     * @return float|null
     */
    public function getLucroTotalAttribute()
    {
        $this->load('items.produto');
        $lucroTotal = 0;
        $temPrecoCusto = false;

        foreach ($this->items as $item) {
            $produto = $item->produto;
            if ($produto && $produto->preco_custo) {
                $temPrecoCusto = true;
                $lucroUnitario = $item->preco_unitario - $produto->preco_custo;
                $lucroTotal += $lucroUnitario * $item->quantidade;
            }
        }

        return $temPrecoCusto ? $lucroTotal : null;
    }

    /**
     * Calcula a margem de lucro da venda
     * @return float|null
     */
    public function getMargemLucroAttribute()
    {
        $lucroTotal = $this->lucro_total;
        if ($lucroTotal === null) {
            return null;
        }

        $custoTotal = 0;
        foreach ($this->items as $item) {
            $produto = $item->produto;
            if ($produto && $produto->preco_custo) {
                $custoTotal += $produto->preco_custo * $item->quantidade;
            }
        }

        if ($custoTotal <= 0) {
            return 0;
        }

        return ($lucroTotal / $custoTotal) * 100;
    }
}
