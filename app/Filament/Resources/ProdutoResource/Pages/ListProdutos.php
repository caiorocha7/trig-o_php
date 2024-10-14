<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions;

class ListProdutos extends ListRecords
{
    protected static string $resource = ProdutoResource::class;

    // Aqui você define a ação "Criar Produto" no canto superior direito
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Adicionar Novo Produto'),
        ];
    }
}
