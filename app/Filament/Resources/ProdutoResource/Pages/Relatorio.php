<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Filament\Resources\ProdutoResource;
use Filament\Resources\Pages\Page;

class Relatorio extends Page
{
    protected static string $resource = ProdutoResource::class;

    protected static string $view = 'filament.resources.produto-resource.pages.relatorio';
}
