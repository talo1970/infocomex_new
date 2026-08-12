<?php

    use App\Models\Minuta;
    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Livewire\Attributes\On;
    use Carbon\Carbon;
    use Illuminate\Support\Number;
    use Livewire\WithPagination;


    new class extends Component {

        use WithPagination;

        public $fecha;
        public $fechaplanilla;
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


        public function mount(): void
        {
            $this->fecha = now();
            $this->fecha_inicio = now()->subMonths(6);
            //dump(date('d-m-Y'));
            $this->fechaplanilla = date('Y-m-d');
        }

        #[Computed]
        public function boletosentidades()
        {
            //Filtrar en una consulta de Base de Datos (Eloquent)
            //$usuariosAntiguos = Usuario::where('created_at', '<=', now()->subMonths(6))->get();
            //$this->fecha_inicio = $this->fechaplanilla->subMonths(6);
            $this->fecha_inicio = strtotime('-6 months', strtotime($this->fechaplanilla));
            $this->fecha_inicio  = date('Y-m-d' , $this->fecha_inicio);

//            dump(' estado: '.$this->estadosFilter. ' fecha_ inicio: '.$this->fecha_inicio. ' fecha: '.$this->fechaplanilla);
            //->whereDate('fecha', $this->fecha);
            $filtro = function ($q) {
                $q->where('estado_id', $this->estadosFilter)
                  ->where('producto_id', 1)
                    ->whereBetween('fecha', [$this->fecha_inicio, $this->fechaplanilla]);
            };

            $bancos = \App\Models\Entidad::bancos()
                    ->where(function ($query) use ($filtro) {
                        $query->whereHas('compradores', $filtro)
                            ->orWhereHas('vendedores', $filtro);
                        })
                ->orderBy('razon_social')->paginate(5);

                return $bancos;
        }

        public function aplicarFiltrosEstados($query)
        {
            if ($this->estadosFilter !== 'all')
            {
                $query->where('estado_id', '=', $this->estadosFilter);
            }
        }

        public function aplicarFiltrosFecha($query)
        {
            if ($this->fechaFilter !== 'all')
            {
                $this->fecha_inicio = $this->fechaplanilla->subMonths(6);
                $query->whereBetween('fecha', [$this->fecha_inicio, $this->fechaplanilla]);
                //$query->where('fecha', '=', $this->fechaFilter);
            }
        }

        #[Computed]
        public function minutaBoletos()
        {
            //return \App\Models\Minuta::where('producto_id', '=', 3)->orderByDesc('fecha')->get();
            return Minuta::boletos()->orWhereHas('comprador', function($q) {
                $q->where('tipo_entidad_id', 2);
            })->orWhereHas('vendedor', function($q) {
                $q->where('tipo_entidad_id', 2);
            })->limit(30)->get();

        }

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::all();
        }

        public function munitasEntidad($entidadId)
        {
            $this->entidadid = $entidadId;
            $this->detalleMinutas =\App\Models\Minuta::where('estado_id', $this->estadosFilter)
                                                    ->where('producto_id', 1)
                                                    ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha])
                                                    ->where(function ($query) {
                                                        $query->where('comprador_id',  $this->entidadid)
                                                              ->orWhere('vendedor_id',  $this->entidadid);
                                                            })->orderByDesc('numero')->get();
        }

    };
?>

<div>

    <livewire:pages::minutas.boletos.create/>
    <livewire:pages::minutas.boletos.edit/>

    <div class="space-y-4">
        <div class="relative mb-4 w-full">
        <flux:heading size="xl" level="1">{{ __('Minuta Cambio') }}</flux:heading>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Minutas de Boletos de Cambio') }}

            <!-- modal-->
            <flux:modal.trigger name="minuta-cambio-crear-modal">
                <flux:badge
                    wire:click="$dispatch('crear-cambio-modal', { modo: 'crear'})"
                    icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nueva Minuta</flux:badge>
            </flux:modal.trigger>
        </flux:heading>
    </div>
        <div class="flex justify-center items-center flex-wrap gap-2">

        <!-- Dropdown por estado -->
{{--        <flux:dropdown>
            <flux:button class="w-48 mr-16 text-left" icon-trailing="chevron-down">ddEstado</flux:button>
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
--}}

            <div class="flex gap-2">
                <flux:label class="w-24 text-xl">Estado: </flux:label>
                <flux:select searchable wire:model.live="estadosFilter" placeholder="Seleccione un Estado">
                    @foreach ($this->estados as $estado)
                        @if ($estado->id == 1)  {{$this->estadosFilter = 1}} @endif
                        <flux:select.option value="{{ $estado->id }}" wire:key="{{ $estado->id }}">{{ $estado->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="ml-32 flex gap-2">
                <flux:label class="w-40 text-xl">Planilla del dìa: </flux:label>
                <flux:date-picker class="w-48" wire:model.live="fechaplanilla" type="input"/>
            </div>
    </div>

        <flux:table class="max-w-9/10" >
            <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
                <flux:table.column>Bancos</flux:table.column>
                <flux:table.column>Fecha</flux:table.column>
                <flux:table.column>Moneda</flux:table.column>
                <flux:table.column>Operación</flux:table.column>
                <flux:table.column>Cliente</flux:table.column>
                <flux:table.column>Importe</flux:table.column>
                <flux:table.column>Equivalencia</flux:table.column>

                <flux:table.column align="center">Acción</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @foreach ($this->boletosentidades as $boletoentidad)
                    {{ $this->munitasEntidad($boletoentidad->id) }}
                        @foreach($this->detalleMinutas as $minuta)
                        <flux:table.row :key="$minuta->id">
                            <!-- Bancos-->
                            <flux:table.cell>
                                {{ $boletoentidad->razon_social }}
                            </flux:table.cell>
                            <!-- Fecha-->
                            <flux:table.cell>
                                {{Carbon::parse($minuta->fecha)->format('d/m/Y') }}
                            </flux:table.cell>
                            <!-- Moneda-->
                            <flux:table.cell>
                                {{ $minuta->moneda?->nombre }}
                            </flux:table.cell>
                            <!-- Operación-->
                            <flux:table.cell>
                                @if ($minuta->comprador_id == $boletoentidad->id)
                                    <flux:badge color="green" inset="top bottom" >
                                        Comprador
                                    </flux:badge>
                                @else
                                    <flux:badge color="red" inset="top bottom">
                                        Vendedor
                                    </flux:badge>
                                @endif
                            </flux:table.cell>
                            <!-- Cliente-->
                            <flux:table.cell>
                                @if ($minuta->comprador_id == $boletoentidad->id)
                                    {{$minuta->vendedor->razon_social }}
                                @else
                                    {{$minuta->comprador->razon_social }}
                                @endif
                            </flux:table.cell>
                            <!-- Importe-->
                            <flux:table.cell align="right">
                                {{Number::format($minuta->importe, locale: 'es')}}
                            </flux:table.cell>
                            <!-- Equivalencia-->
                            <flux:table.cell align="right">
                                {{Number::format($minuta->equivalente, locale: 'es')}}
                            </flux:table.cell>
                            <!-- Edit-->
                            <flux:table.cell align="center" class="w-24">
                                 <flux:modal.trigger name="minuta-boleto-edit">
                                    <flux:tooltip content="Editar Minuta">
                                        <flux:badge color="indigo" as="button"
                                                    wire:click="$dispatch('minutaEdit', {minuta: '{{$minuta->id}}'})"
                                                    icon="pencil" class="cursor-pointer" >
                                        </flux:badge>
                                    </flux:tooltip>
                                </flux:modal.trigger>
                            </flux:table.cell>
                    </flux:table.row>
                    @endforeach
                @endforeach
            </flux:table.rows>
        </flux:table>

        {{ $this->boletosentidades->links() }}
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
</div>
