<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emitente extends Model
{
    use HasFactory;

    protected $table = 'emitente';

    protected $fillable = [
        'cnpj',
        'razao_social',
        'nome_fantasia',
        'inscricao_estadual',
        'inscricao_municipal',
        'crt',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'codigo_municipio',
        'municipio',
        'uf',
        'cep',
        'telefone',
        'email'
    ];

    public function notasFiscais()
    {
        return $this->hasMany(NotaFiscal::class);
    }
}

