<?php

    use App\Models\Minuta;
    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Carbon\Carbon;
    use Livewire\WithPagination;

    new class extends Component {
        use WithPagination;

        public \App\Models\Producto $producto;
        public $producto_id;
        public $fecha;
        public $fecha_inicio;
        public $estadosFilter = '1';
        public $perPage       = 10;

        public function mount(\App\Models\Producto $producto): void
        {
            $this->producto     = $producto;
            $this->producto_id  = $producto->id;
            $this->fecha        = now();
            $this->fecha_inicio = now()->subMonths(6);
/*
            $this->minutasProducto = Minuta::where('producto_id', $producto->id)
                                           ->where('estado_id', $this->estadosFilter)->whereBetween('fecha', [
                    $this->fecha_inicio,
                    $this->fecha
                ])->withAggregate('entidad_cliente', 'razon_social')->orderBy('entidad_cliente_razon_social')->get();
*/
        }

        #[Computed]
        public function minutasproductos()
        {
            $aux                = strtotime('-6 months', strtotime($this->fecha));
            $this->fecha_inicio = date('Y-m-d', $aux);
            dump('producto_id ' . $this->producto->id . ' estado: ' . $this->estadosFilter . ' fecha_ inicio: ' . $this->fecha_inicio . ' fecha: ' . $this->fecha);

            return \App\Models\Minuta::where('producto_id', $this->producto->id)
                                     ->where('estado_id', $this->estadosFilter)->whereDate('fecha', '<=', $this->fecha)
                                     ->withAggregate('entidad_cliente', 'razon_social')
                                     ->orderBy('entidad_cliente_razon_social')->paginate($this->perPage);
        }

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::all();
        }

        public function aplicarFiltrosEstados($query)
        {
            if ($this->estadosFilter !== 'all') {
                $query->where('estado_id', '=', $this->estadosFilter);
            }
        }

    };
?>

<div>
    <div class="space-y-4">
        <div class="relative mb-4 w-full">
            <flux:heading size="xl" level="1">{{ __('Planilla de '. $this->producto->nombre) }}</flux:heading>
            <flux:subheading size="lg"
                             class="mb-4 flex justify-between">{{ __('Administración de '. $this->producto->nombre) }}

                <!-- modal-->
                <flux:modal.trigger name="minuta-cambio-crear-modal">
                    <flux:badge
                        wire:click="$dispatch('crear-cambio-modal', { modo: 'crear'})"
                        icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nueva Minuta
                    </flux:badge>
                </flux:modal.trigger>
            </flux:heading>
        </div>
        <div class="flex justify-center items-center flex-wrap gap-2">

            <!-- Dropdown por estado -->
            <flux:dropdown>
                <flux:button class="w-48 mr-16 text-left" icon-trailing="chevron-down">Estado</flux:button>
                <flux:menu searchable>
                    <flux:menu.radio.group wire:model.live="estadosFilter">
                        <flux:menu.radio wire:click="$set('estadosFilter', 'all')" value="all"
                                         :checked="$estadosFilter === 'all'">
                            Todos
                        </flux:menu.radio>
                        @foreach($this->estados as $estado)
                            <flux:menu.radio wire:click="$set('estadosFilter', '{{$estado->id}}')"
                                             value="{{$estado->id}}"
                                             :checked="$estadosFilter === '{{$estado->nombre}}'">
                                {{$estado->nombre}}
                            </flux:menu.radio>
                        @endforeach

                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

            <div class="flex gap-2">
                <flux:label class="w-40 text-xl">Planilla del dìa:</flux:label>
                <flux:date-picker class="w-48" wire:model.live="fecha" type="input" />

            </div>
        </div>

        <flux:table class="max-w-9/10">
            <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600">
                <flux:table.column>clientes</flux:table.column>
                <flux:table.column>Fecha</flux:table.column>
                <flux:table.column>Plazo</flux:table.column>
                <flux:table.column>Vencimiento</flux:table.column>
                <flux:table.column>Importe</flux:table.column>
                <flux:table.column>Cantidad</flux:table.column>
                <flux:table.column align="center">Acción</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @foreach ($this->minutasproductos as $minuta)
                    <flux:table.row :key="$minuta->id">
                        <!-- clientes-->
                        <flux:table.cell>
                            {{ $minuta->entidad_cliente->razon_social }}
                        </flux:table.cell>
                        <!-- Fecha-->
                        <flux:table.cell>
                            {{Carbon::parse($minuta->fecha)->format('d/m/Y') }}
                        </flux:table.cell>
                        <!-- plazo-->
                        <flux:table.cell>
                            {{ $minuta->plazo }}
                        </flux:table.cell>
                        <!-- vto-->
                        <flux:table.cell>
                            {{Carbon::parse($minuta->fecha_vencimiento)->format('d/m/Y') }}
                        </flux:table.cell>
                        <!-- importe -->
                        <flux:table.cell>
                            {{$minuta->importe }}
                        </flux:table.cell>
                        <!-- Cantidad -->
                        <flux:table.cell>
                            {{$minuta->cantidad }}
                        </flux:table.cell>

                        <!-- Edit-->
                        <flux:table.cell align="center" class="w-24">

                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        {{ $this->minutasproductos->links() }}

    </div>
</div>
