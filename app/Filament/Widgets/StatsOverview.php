<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Pedido;
use App\Models\Produto;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalPedidos = Pedido::count();
        $totalProdutos = Produto::count();
        $totalFinanceiro = Pedido::sum('valor_final'); // Suponha que o campo 'valor_final' exista

        return [
            Card::make('Total de Pedidos', $totalPedidos)
                ->description('Total de pedidos registrados')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('success'),

            Card::make('Total de Produtos', $totalProdutos)
                ->description('Produtos cadastrados no sistema')
                ->descriptionIcon('heroicon-o-folder')
                ->color('primary'),

            Card::make('Total Financeiro', 'R$ ' . number_format($totalFinanceiro, 2, ',', '.'))
                ->description('Total de vendas realizadas')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('warning'),
        ];
    }
}
