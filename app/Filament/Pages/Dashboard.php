<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; // Ícone de navegação

    // Adicionando os widgets ao dashboard
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class, // Referenciando o widget correto
        ];
    }
}
