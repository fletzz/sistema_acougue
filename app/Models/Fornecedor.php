<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $fillable = ['razao_social','nome_fantasia','cnpj','telefone','email','endereco'];

    protected $table = 'fornecedores';

    
}
