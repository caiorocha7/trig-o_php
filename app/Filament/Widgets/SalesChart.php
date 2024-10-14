<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class SalesChart extends Widget
{
    protected static ?string $heading = 'Vendas Semanais';

    protected function getData(): array
    {
        // Atualizando a query para usar a coluna 'valor'
        $salesByWeek = Pedido::selectRaw('DATE_TRUNC(\'week\', created_at) as week, SUM(valor) as total')
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        $chart = (new LarapexChart)->lineChart()
            ->setTitle('Vendas Semanais')
            ->setXAxis($salesByWeek->pluck('week')->toArray())
            ->addData('Total de Vendas', $salesByWeek->pluck('total')->toArray());

        return [
            'chart' => $chart,
        ];
    }

    public function render(): View
    {
        return view('filament.widgets.sales-chart', $this->getData());
    }
}
