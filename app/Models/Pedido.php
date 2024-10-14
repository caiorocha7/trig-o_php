<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_nome',
        'contato',
        'data',
        'valor_parcial',
        'valor_final',
    ];

    // Relacionamento entre Pedido e Produto
    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
                    ->withPivot('preco', 'quantidade')
                    ->withTimestamps();
    }

    protected static function booted()
    {
        static::saving(function ($pedido) {
            // Calcula o valor parcial somando os subtotais dos produtos do pedido
            $valorParcial = $pedido->produtos->sum(function ($produto) {
                return $produto->pivot->preco * $produto->pivot->quantidade;
            });

            // Define o valor parcial
            $pedido->valor_parcial = $valorParcial;

            // Define o valor final do pedido
            $pedido->valor_final = $valorParcial;

            // Evita valores nulos
            if ($pedido->valor_final < 0) {
                $pedido->valor_final = 0;
            }
        });
    }
}
