<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Resources\Pages\ListRecords;

class ListProdutos extends ListRecords
{
    protected static string $resource = ProdutoResource::class;

    // Método opcional para definir o título da página
    public function getTitle(): string
    {
        return 'Produtos';
    }
}
