<div>
    <div>
        <label for="startDate">Data Inicial:</label>
        <input type="date" wire:model="startDate" id="startDate">
    </div>
    <div>
        <label for="endDate">Data Final:</label>
        <input type="date" wire:model="endDate" id="endDate">
    </div>

    <button wire:click="$refresh" class="btn btn-primary mt-4">Filtrar</button>

    <div class="mt-8">
        @isset($chart)
            {!! $chart->container() !!}
            <script src="{{ $chart->cdn() }}"></script>
            {{ $chart->script() }}
        @else
            <p>Nenhum dado disponível para gerar o gráfico.</p>
        @endisset
    </div>
</div>
