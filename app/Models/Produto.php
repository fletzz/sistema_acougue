<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    
    use HasFactory;

    protected $table = 'produtos';

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function vendaItems() {
        return $this->hasMany(VendaItem::class);
        
    }

}
