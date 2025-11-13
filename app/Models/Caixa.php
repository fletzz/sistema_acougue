<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    use HasFactory;
    protected $table = 'caixas';

    protected $fillable = [
        'id',
        'descricao',
        'status',
        'saldo_atual'
    ];
}