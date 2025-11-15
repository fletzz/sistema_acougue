<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Csosn extends Model
{
    use HasFactory;

    protected $table = 'csosn';

    protected $primaryKey = 'codigo';

    public $incrementing = false;

    protected $fillable = [
        'codigo',
        'descricao'
    ];
}

