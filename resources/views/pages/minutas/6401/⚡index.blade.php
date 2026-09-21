<?php

    use App\Models\Minuta;
    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Livewire\Attributes\On;
    use Carbon\Carbon;
    use Illuminate\Support\Number;
    use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $fecha;
    //public $fecha = date('Y-m-d', strtotime('+1 day'));
    public $fecha_inicio ;

    //$tomorrow = date('Y-m-d H:i:s', strtotime('+1 day'));

    public $estadosFilter = '1';
    public $fechaFilter = 'all';
    public $tipoEntidad = '1';

    public $sortBy = "fecha";
    public $perPage = 10;
    public $sortDirection = "desc";

    public $entidadid;
    public $detalleMinutas;

    protected $listeners = ['refreshComponent' => '$refresh'];

    private function esVendedor(): bool
    {
        return auth()->user()->hasRole('vendedor');
    }

    #[Computed]
    public function minutasCom6401()
    {
        $this->fecha = now();
        $this->fecha_inicio = now()->subMonths(6);
        //(' estado: '.$this->estadosFilter. ' fecha_ inicio: '.$this->fecha_inicio. ' fecha: '.$this->fecha);

        if ($this->esVendedor()) {
            //dump(' estado: '.$this->estadosFilter. ' fecha_ inicio: '.$this->fecha_inicio. ' fecha: '.$this->fecha.' es vendedor');
            return Minuta::com6401()
                        ->where('usuario_vendedor_id', auth()->id())
                        ->where('estado_id', $this->estadosFilter)
                        ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha])
                        ->withAggregate('entidad_cliente','razon_social')
                        ->orderBy('entidad_cliente_razon_social')
                        ->paginate(20);
            } else {
            //dump(' estado: '.$this->estadosFilter. ' fecha_ inicio: '.$this->fecha_inicio. ' fecha: '.$this->fecha);
            return Minuta::com6401()
                         ->where('estado_id', $this->estadosFilter)
                         ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha])
                         ->withAggregate('entidad_cliente','razon_social')
                         ->orderBy('entidad_cliente_razon_social')
                         ->paginate(20);
        }
    }

    #[Computed]
    public function estados()
    {
        return \App\Models\Estado::all();
    }

};
?>

<div class="space-y-4">
    <div class="-mb-4">
        <livewire:pages::minutas.6401.create/>
        <livewire:pages::minutas.6401.edit/>
    </div>
    <div class="relative mb-4 w-full bg-gradient-to-r from-red-50 to-red-300 ">
            <flux:heading size="xl" level="1" class="ml-2 text-black">{{ __('Minuta Com-6401') }}</flux:heading>
                <flux:subheading size="lg" class="mb-4 ml-2 dark:text-black flex justify-between">{{ __('Administración de minutas de Com. 6401') }}
                <!-- modal-->
                <flux:modal.trigger name="minuta-6401-crear-modal">
                    <flux:badge
                        wire:click="$dispatch('crear-6401-modal', { modo: 'crear'})"
                        icon="plus-circle" class="cursor-pointer" variant="solid" color="red">Nueva Minuta
                    </flux:badge>
                </flux:modal.trigger>
            </flux:heading>
        <flux:separator variant="subtle" />
    </div>





    <div class="flex justify-center items-center flex-wrap gap-2">

        <!-- Dropdown por estado -->
        <flux:dropdown>
            <flux:button class="w-48 mr-16 text-left" icon-trailing="chevron-down">Estado</flux:button>
            <flux:menu searchable>
                <flux:menu.radio.group wire:model.live="estadosFilter">
                    <flux:menu.radio wire:click="$set('estadosFilter', 'all')" value="all" :checked="$estadosFilter === 'all'">
                        Todos
                    </flux:menu.radio>
                    @foreach($this->estados as $estado)
                        <flux:menu.radio wire:click="$set('estadosFilter', '{{$estado->id}}')" value="{{$estado->id}}" :checked="$estadosFilter === '{{$estado->nombre}}'">
                            {{$estado->nombre}}
                        </flux:menu.radio>
                    @endforeach

                </flux:menu.radio.group>
            </flux:menu>
        </flux:dropdown>

        <div class="flex gap-2">
            <flux:label class="w-40 text-xl">Planilla del día: </flux:label>
            <flux:date-picker class="w-48" wire:model.live="fecha" type="input"/>
        </div>
    </div>

    <flux:table class="max-w-9/10" >
            <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
                <flux:table.column>clientes</flux:table.column>
                <flux:table.column>Fecha</flux:table.column>
                <flux:table.column>Desde</flux:table.column>
                <flux:table.column>Hasta</flux:table.column>
                <flux:table.column>Cantidad</flux:table.column>
                <flux:table.column>Banco</flux:table.column>
                <flux:table.column align="center">Acción</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @foreach ($this->minutasCom6401 as $minuta)
                        <flux:table.row :key="$minuta->id">
                            <!-- clientes-->
                            <flux:table.cell>
                                {{ $minuta->entidad_cliente?->razon_social }}
                            </flux:table.cell>
                            <!-- Fecha-->
                            <flux:table.cell>
                                {{Carbon::parse($minuta->fecha)->format('d/m/Y') }}
                            </flux:table.cell>
                            <!-- desde-->
                            <flux:table.cell>
                                {{ $minuta->periodo_desde }}
                            </flux:table.cell>
                            <!-- hasta-->
                            <flux:table.cell>
                                {{$minuta->periodo_hasta }}
                            </flux:table.cell>
                            <!-- cantidad-->
                            <flux:table.cell align="center">
                                {{$minuta->periodo_cantidad }}
                            </flux:table.cell>
                            <!-- bancos-->
                            <flux:table.cell>
                                    {{$minuta->bcra->razon_social }}
                            </flux:table.cell>
                            <!-- Edit-->
                            <flux:table.cell align="center" class="w-24">
                                <flux:modal.trigger name="editar-6401-modal">
                                    <flux:tooltip content="Editar minuta">
                                        <flux:badge color="indigo" as="button"
                                                    wire:click="$dispatch('editar-minuta-modal', { minuta: '{{$minuta}}'})"
                                                    icon="pencil" class="cursor-pointer" >
                                        </flux:badge>
                                    </flux:tooltip>
                                </flux:modal.trigger>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
            </flux:table.rows>
    </flux:table>

    {{ $this->minutasCom6401->links() }}
        {{--
            {{$this->minutaBoletos->count()}}<br>
            @foreach($this->minutaBoletos as $boleto)
                {{ $boleto->numero }} - {{Carbon::parse($boleto->fecha)->format('Y')}}
                {{$boleto->comprador->razon_social}} - {{$boleto->vendedor->razon_social}}
                {{$boleto->moneda->nombre}} -
                {{$boleto->cantidad}} -{{$boleto->tipo_cambio}} - {{$boleto->equivalente}}
                <br>
            @endforeach
        --}}
</div>
