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
}
