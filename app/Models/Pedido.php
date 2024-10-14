<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_nome',
        'contato',
        'data',
        'valor',
        'valor_final',
    ];

    // Relacionamento muitos-para-muitos com o modelo Produto
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }
}
