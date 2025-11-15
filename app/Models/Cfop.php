<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cfop extends Model
{
    use HasFactory;

    protected $table = 'cfop';

    protected $primaryKey = 'codigo';

    public $incrementing = false;

    protected $fillable = [
        'codigo',
        'descricao'
    ];
}

