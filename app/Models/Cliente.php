<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    use HasFactory;

    protected $fillable = ['nome','cpf_cnpj','telefone','email'];
    protected $table = 'clientes';

    public function vendas() {
        return $this->hasMany(Venda::class);
    }
}
