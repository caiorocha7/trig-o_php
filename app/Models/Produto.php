<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    // Definir os campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'codigo',
        'nome',
        'secao',
        'preco',
        'quantidade',
        'unidade',
    ];

    // Definir os tipos de dados para cada atributo
    protected $casts = [
        'codigo' => 'string',
        'nome' => 'string',
        'secao' => 'string',
        // 'preco' => 'decimal:2',
        'quantidade' => 'integer',
        'unidade' => 'decimal:2',
    ];

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_produto')
            ->withPivot('quantidade')
            ->withTimestamps();
    }
}
