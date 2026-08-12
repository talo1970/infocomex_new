<?php

use Livewire\Component;

new class extends Component
{

    public $facturaAnio;
    public $data = [];

    public $productofacturado;
    //hacer como lo de cuadro de saj


    public function mount(): void
    {
        //cuadros
        $this->productofacturado = \App\Models\Producto::withCount(['facturacion' => function ($query) {
            $query->whereYear('fecha', 2025);
//        $query->whereYear('fecha', 2025)->where('vendedor_id', 5);
        }])->orderBy('id')->get();


        // esto es para graficar
        /*

                $productofacturado = \App\Models\Producto::withCount(['facturacion' => function ($query) {
                    $query->whereYear('fecha', 2026);
                }])->orderBy('id')->get();

                foreach ($productofacturado as $anios) {
                        $this->data[] = [
                            'producto' => $anios->abreviado,
                            'total' => $anios->facturacion_count
                        ];
                }
                $this->data = [
                        ['month' => 'Enero', 'tickets' => 130],
                        ['month' => 'Febrero', 'tickets' => 200],
                        ['month' => 'Marzo', 'tickets' => 150],
                        ['month' => 'Abril', 'tickets' => 150],
                        ['month' => 'Mayo', 'tickets' => 110],
                        ['month' => 'Junio', 'tickets' => 50],
                        ['month' => 'Julio', 'tickets' => 100],
                        ['month' => 'Agosto', 'tickets' => 90],
                        ['month' => 'Septiembre', 'tickets' => 180],
                        ['month' => 'Octubre', 'tickets' => 110],
                        ['month' => 'Noviembre', 'tickets' => 75],
                        ['month' => 'Diciembre', 'tickets' => 95],
                        // ...
                    ];
                */

    }

};
?>
<!-- <div class="mt-16"> -->
<div>
    <div class="w-full rounded-sm border border-sky-400 bg-sky-100 px-1 py-1 text-sky-700 text-center font-bold dark:border-sky-500 dark:bg-sky-300">
        Cantidades de producto facturado en el 2026
    </div>
    <ul role="list" class="gay-y-2 m-3 grid grid-cols-1 gap-x-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-3 xl:grid-cols-2">
        @foreach($this->productofacturado as $producto)
            @if($producto->facturacion_count > 0)
            <li class="relative col-span-1 flex rounded-md shadow-xs">
                <div>
                    <flux:badge color="{{$producto->color_web}}" size="lg" variant="solid" class="shrink-0 items-center justify-center w-16 text-sm font-semibold text-white">
                        {{$producto->facturacion_count}}
                    </flux:badge>
                </div>
                <div
                    class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                    <div class="flex-1 truncate px-4 py-1 text-sm">
                                <span class="font-medium text-gray-900 hover:text-gray-600">
                                    {{ ucfirst($producto->abreviado) }}
                                </span>
                    </div>
                </div>
            </li>
            @endif
        @endforeach
    </ul>


    {{--
    <flux:chart wire:model="data" class="aspect-[3/1]">
        <flux:chart.svg>
            <flux:chart.bar field="total" class="text-blue-300 dark:text-blue-700" />
            <flux:chart.axis axis="x" field="producto">
                <flux:chart.axis.tick />
            </flux:chart.axis>
            <flux:chart.axis axis="y">
                <flux:chart.axis.grid />
                <flux:chart.axis.tick />
            </flux:chart.axis>
        </flux:chart.svg>
        <flux:chart.tooltip>
            <flux:chart.tooltip.value field="total" label="Total" />
        </flux:chart.tooltip>
    </flux:chart>
    --}}
</div>
