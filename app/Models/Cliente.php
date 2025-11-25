<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    use HasFactory;

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'telefone',
        'email',
        'endereco',
        'inscricao_estadual',
        'inscricao_municipal',
        'indicador_ie',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cep',
        'codigo_municipio_ibge',
        'municipio',
        'uf'
    ];
    protected $table = 'clientes';

    public function vendas() {
        return $this->hasMany(Venda::class);
    }

    /**
     * Verifica se o cliente é uma Pessoa Jurídica (CNPJ)
     * @return bool
     */
    public function isPessoaJuridica()
    {
        if (!$this->cpf_cnpj) {
            return false;
        }

        $cpfCnpj = preg_replace('/[^0-9]/', '', $this->cpf_cnpj);
        
        // CNPJ tem 14 dígitos, CPF tem 11 dígitos
        return strlen($cpfCnpj) == 14;
    }

    /**
     * Verifica se o cliente é uma Pessoa Física (CPF)
     * @return bool
     */
    public function isPessoaFisica()
    {
        if (!$this->cpf_cnpj) {
            return false;
        }

        $cpfCnpj = preg_replace('/[^0-9]/', '', $this->cpf_cnpj);
        
        return strlen($cpfCnpj) == 11;
    }
}
