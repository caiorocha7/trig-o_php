<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SalesChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; // Ícone de navegação

    // Usar o método getHeaderWidgets para exibir os widgets no dashboard
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,  // Widget de estatísticas
            SalesChart::class,  // Gráfico de vendas
        ];
    }
}
