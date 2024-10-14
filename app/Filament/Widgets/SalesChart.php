<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Filament\Widgets\Widget;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Carbon;

class SalesChart extends Widget
{
    protected static ?string $heading = 'Vendas Semanais';
    protected static string $view = 'filament.widgets.sales-chart'; // Especifique a view correta

    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getData(): array
    {
        $query = Pedido::query();

        // Filtro de datas
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $salesByWeek = $query->selectRaw('DATE_TRUNC(\'week\', created_at) as week, SUM(valor) as total')
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
}
