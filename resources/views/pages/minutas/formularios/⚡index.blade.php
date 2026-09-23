<?php

    use App\Models\Minuta;
    use App\Models\Producto;
    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Livewire\WithPagination;
    use Carbon\Carbon;

    new class extends Component {
        use WithPagination;

        public $producto;
        public $producto_id;
        public $minutasProducto;
        public $fecha;
        //public $fecha = date('Y-m-d', strtotime('+1 day'));
        public $fecha_inicio;
        //$tomorrow = date('Y-m-d H:i:s', strtotime('+1 day'));

        public $estadosFilter = '1';
        public $perPage       = 10;

        protected $listeners = ['refreshComponent' => '$refresh'];

        public function mount(Producto $producto): void
        {
            $this->producto    = $producto;
            $this->producto_id = $producto->id;

            $this->fecha        = now();
            $this->fecha_inicio = now()->subMonths(6);

            /*
            $this->minutasProducto = Minuta::where('producto_id', $producto->id)
                                           ->where('estado_id', $this->estadosFilter)
                                           ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha])
                                           ->withAggregate('entidad_cliente', 'razon_social')->orderBy('entidad_cliente_razon_social')->get();

                                    return Minuta::com6401()
                                                 ->where('estado_id', $this->estadosFilter)
                                                 ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha])
                                                 ->withAggregate('entidad_cliente','razon_social')
                                                 ->orderBy('entidad_cliente_razon_social')
                                                 ->paginate(20);
                                */
//            dump(' estado: '.$this->estadosFilter. ' fecha_ inicio: '.$this->fecha_inicio. ' fecha: '.$this->fecha);
        }

        #[Computed]
        public function minutasproductos()
        {

            $aux = strtotime('-6 months', strtotime($this->fecha));
            $this->fecha_inicio = date('Y-m-d', $aux);
            //$this->fecha= Carbon::parse($this->fecha)->format('Y-m-d');
            //$this->fecha_inicio = Carbon::parse($this->fecha_inicio)->format('Y-m-d');

            //dump('Producto: '.$this->producto_id.' estado: '.$this->estadosFilter. ' fecha_ inicio: '.$this->fecha_inicio. ' fecha: '.$this->fecha);

            /*
            $minutas = \App\Models\Entidad::bancos()
                                         ->where(function ($query) use ($filtro) {
                                             $query->whereHas('compradores', $filtro)
                                                   ->orWhereHas('vendedores', $filtro);
                                         })
                                         ->orderBy('razon_social')->paginate(5);
            //->get();
            //->pluck('razon_social', 'id');
*/
            return  \App\Models\Minuta::where('producto_id', $this->producto->id)
                                        ->where('estado_id', $this->estadosFilter)
                                        ->whereDate('fecha', '<=', $this->fecha)
                                        ->withAggregate('entidad_cliente','razon_social')
                ->orderByDesc('fecha')
                                        ->orderBy('entidad_cliente_razon_social')
                                        ->paginate($this->perPage);
        }

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::all();
        }

        public function aplicarFiltrosEstados($query)
        {
            if ($this->estadosFilter !== 'all')
            {
                $query->where('estado_id', '=', $this->estadosFilter);
            }
        }

    };
?>

<div class="space-y-4">
    <div class="-mb-4">
        <livewire:pages::minutas.formularios.create/>
        <livewire:pages::minutas.formularios.edit/>
    </div>
    <div class="relative mb-4 w-full bg-gradient-to-r from-red-50 to-red-300 ">
    <flux:heading size="xl" level="1">{{ __('Minutas de '. $this->producto->nombre) }}</flux:heading>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de '. $this->producto->nombre) }}
            <!-- modal-->
            <flux:modal.trigger name="minuta-crear-modal">
                <flux:badge
                    wire:click="$dispatch('minutaCrear', { producto: '{{$this->producto}}'})"
                    icon="plus-circle" class="cursor-pointer" variant="solid" color="red">Nueva Minuta
                </flux:badge>
            </flux:modal.trigger>
        </flux:heading>
        <flux:separator variant="subtle" />
    </div>
    <div class="flex justify-center items-center flex-wrap gap-2">

        <!-- Dropdown por estado -->
        <flux:label class="mr-2 text-xl">Estado:</flux:label>
        <flux:select searchable wire:model.live="estadosFilter" class="max-w-[16rem] mr-8">
            {{--<flux:select.option value="all">Todos los estados</flux:select.option>--}}
            @foreach ($this->estados as $estado)
                <flux:select.option value="{{ $estado->id }}" wire:key="{{ $estado->id }}">{{ $estado->nombre }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="flex gap-2">
            <flux:label class="w-40 text-xl">Planilla del dìa:</flux:label>
            <flux:date-picker class="w-48" wire:model.live="fecha" type="input" />

        </div>
    </div>

    <flux:table class="max-w-9/10">
        <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600">
            <flux:table.column>clientes</flux:table.column>
            <flux:table.column>Fecha</flux:table.column>
            <flux:table.column>Desde</flux:table.column>
            <flux:table.column>hasta</flux:table.column>
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
                    <!-- desde-->
                    <flux:table.cell>
                        {{ $minuta->anio_desde }}
                    </flux:table.cell>
                    <!-- hasta-->
                    <flux:table.cell>
                        {{$minuta->anio_hasta }}
                    </flux:table.cell>
                    <!-- bancos-->
                    <flux:table.cell>
                        {{$minuta->anio_cantidad }}
                    </flux:table.cell>
                    <!-- Edit-->
                    <flux:table.cell align="center" class="w-24">
                        <flux:modal.trigger name="minuta-editar-modal">
                            <flux:tooltip content="Editar minuta">
                                <flux:badge color="indigo" as="button"
                                            wire:click="$dispatch('minutaEdit', {minuta: '{{$minuta->id}}'})"
                                            icon="pencil" class="cursor-pointer" >
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    {{ $this->minutasproductos->links() }}

</div>
