<?php

    use App\Models\Minuta;
    use Flux\Flux;
    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Carbon\Carbon;
    use Livewire\WithPagination;

    new class extends Component {
        use WithPagination;

        public \App\Models\Producto $producto;
        public                      $producto_id;
        public                      $fecha;
        public                      $fecha_inicio;
        public                      $estadosFilter = '1';
        public                      $perPage       = 10;
        public                      $valor;
        public                      $importe       = 1;
        public                      $nuevoimporte  = 1;


        public function mount(\App\Models\Producto $producto): void
        {
            $this->producto     = $producto;
            $this->producto_id  = $producto->id;
            $this->fecha        = now()->format('Y-m-d');
            $this->fecha_inicio = now()->subMonths(6);

            $this->valor = \App\Models\configuracion::where('nombre', 'valor_suscripcion')->first();

            $this->importe      = $this->valor->valor_importe;
            $this->nuevoimporte = $this->valor->valor_importe;

            // dd($this->valor->valor_importe);
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
                                     ->withAggregate('entidad_cliente', 'razon_social')->orderByDesc('fecha')
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

        public function cambiarImporte()
        {
            $this->validate([
                                'nuevoimporte' => [
                                    'required',
                                    'numeric',
                                    'min:1'
                                ]
                            ], [
                                'nuevoimporte.required' => 'es obligatorio',
                                'nuevoimporte.numeric'  => 'deber ser numérico'
                            ]);

            $this->valor->valor_importe = $this->nuevoimporte;
            $this->valor->save();
            $this->importe      = $this->nuevoimporte;

            Flux::modal('delete-profile')->close();
            //Flux::toast(heading: 'Editar', text: 'grabo por esta sucio', variant: 'success', position: 'top end');


        }

    };
?>

<div class="space-y-4">
    <div class="-mb-4">
        <livewire:pages::minutas.suscripciones.create />
        <livewire:pages::minutas.suscripciones.edit />
    </div>
    <div class="space-y-4">
        <div class="relative mb-4 w-full bg-gradient-to-r from-teal-50 to-teal-300 ">

            <flux:heading size="xl" level="1">{{ __('Planilla de '. $this->producto->nombre) }}</flux:heading>
            <flux:subheading size="lg"
                             class="mb-4 flex justify-between">{{ __('Administración de '. $this->producto->nombre) }}
                <!-- modal-->
                <flux:modal.trigger name="suscripcion-crear-modal">
                    <flux:badge
                        wire:click="$dispatch('minutaCrear', { producto: '{{$this->producto}}', valor: '{{$this->importe}}'})"
                        icon="plus-circle" class="cursor-pointer" variant="solid" color="teal">Nueva Minuta
                    </flux:badge>
                </flux:modal.trigger>
            </flux:heading>
            <flux:separator variant="subtle" />

        </div>
        <div class="flex justify-between items-center flex-wrap gap-2 mx-32">

            <!-- Dropdown por estado -->
            <div class="flex gap-2">
                <flux:label class="mr-2 text-xl">Estado:</flux:label>
                <flux:select searchable wire:model.live="estadosFilter" class="max-w-[16rem] mr-8">
                    {{--<flux:select.option value="all">Todos los estados</flux:select.option>--}}
                    @foreach ($this->estados as $estado)
                        <flux:select.option value="{{ $estado->id }}"
                                            wire:key="{{ $estado->id }}">{{ $estado->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="flex gap-2">
                <flux:label class="w-40 text-xl">Planilla del dìa:</flux:label>
                <flux:date-picker class="w-48" wire:model.live="fecha" type="input" />
            </div>
            {{-- valor de las suscripción--}}
            <div class="flex justify-center items-center">
                <div class="bg-red-100 mr-4">
                    Valor Suscripción U$D:&emsp;{{$this->importe}}
                </div>
                <flux:modal.trigger name="delete-profile">
                    <flux:tooltip content="Editar importe">
                        <flux:badge color="indigo" as="button"
                                    wire:click="$dispatch('minutaEdit', {valor: '{{$this->importe}}'})"
                                    icon="pencil" class="cursor-pointer">
                        </flux:badge>
                    </flux:tooltip>
                </flux:modal.trigger>
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
                            <flux:modal.trigger name="minuta-editar-modal">
                                <flux:tooltip content="Editar minuta">
                                    <flux:badge color="indigo" as="button"
                                                wire:click="$dispatch('minutaEdit', {minuta: '{{$minuta->id}}'})"
                                                icon="pencil" class="cursor-pointer">
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

    <flux:modal name="delete-profile" class="min-w-[30rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Cambiar Importe ?</flux:heading>
                <flux:input class="w-32" label="Importe" wire:model="nuevoimporte" />
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button wire:click="cambiarImporte" type="submit" variant="danger">Grabar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
