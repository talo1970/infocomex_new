<?php

    use Livewire\Attributes\On;
    use Livewire\Component;

    new class extends Component {
        public $dias = 0;
        public $minuta_id;

        public $fin;

        public $nombre;
        public $inicio;
        public $periodo;
        public $peridoinicial;
        public $cantidadDias = 0;
        public $cantidadDiasInicial = 0;

        public $importexdia;
        public $importe;
        public $archivoAttributes =[];

        #[On('crear-suscriptor')]
        public function crearSuscriptorr($minuta, $inicial, $vencimiento, $valor): void
        {
            $this->minuta_id = $minuta;
            $this->inicio = new DateTime($inicial);
            $this->peridoinicial = $this->inicio;
            $this->fin  = new Datetime($vencimiento);

            $intervalo = $this->inicio->diff($this->fin);
            $this->cantidadDias =  $intervalo->days;
            $this->cantidadDiasInicial = $this->cantidadDias;

            $this->importexdia = $valor / 30;
            $this->importe = round($this->importexdia *$this->cantidadDias, 2);



        }

        public function updatedInicio()
        {
            //dd($this->inicio);
            $this->periodo = 0;
            $fechaInicio = \Carbon\Carbon::parse($this->inicio);
            $fechaFin = \Carbon\Carbon::parse($this->fin);

            $this->cantidadDias = $fechaInicio->diffInDays($this->fin);
            //$this->importe = round($this->importexdia *$this->cantidadDias, 2);
            $this->actualizarImporte();
        }

        public function updatedPeriodo()
        {
            $this->cantidadDias = $this->cantidadDiasInicial;
            $this->inicio = $this->peridoinicial;
            $this->actualizarImporte();
        }

        public function actualizarImporte()
        {
            $this->importe = round($this->importexdia *$this->cantidadDias, 2);

         }


        public function grabarContacto()
        {
            // tener idea de grabar suscriptor sin tener la minuta

            $archivoAttributes = [
                'id'          => null,
                'nombre'      => $this->nombre,
                'inicio'      => $this->inicio,
                'vencimiento' => $this->fin,
                'dias'        => $this->cantidadDias,
                'importe'     => $this->importe,
                'periodo'     => $this->periodo,
            ];
               // dd($archivoAttributes);
//            una ves que lo quiere grabar va a index de suscriptores para actualizar la tabla
//                    $this->dispatch('uploadedArchivo', archivo: $archivoAttributes)->to(CrearMensaje::class);

            //$this->reset();
            Flux::modal('crear-suscriptor-modal')->close();
            $this->dispatch('refreshComponent', datos: $archivoAttributes)->to('pages::minutas.suscripciones.create');
        }



    };
?>

<div>
    <flux:modal name="crear-suscriptor-modal" class="min-w-[64rem]">
        <form wire:submit="grabarContacto" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center" size="lg">Datos del Suscriptor</flux:heading>
            </div>

            <flux:card>
                {{-- 1° fila --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Contacto --}}
                    <div class="w-3/5">
                        <flux:input wire:model="nombre" label="Contacto" placeholder="Ingrese un nombre" />
                    </div>
                    {{-- fecha inicion --}}
                    <div>
                        <flux:date-picker type="input" label="Fecha inicio" wire:model.live="inicio" />
                    </div>
                    <div>
                        <flux:date-picker :disabled="true" type="input" wire:model="fin" label="Vencimiento"/>

                    </div>
                </div>
                {{-- 2° fila --}}
                <div class="mt-4 flex w-full flex-row justify-between items-center space-x-4 text-center">
                    <div>
                        <Flux:field variant="inline">
                            <flux:label class="mr-4">Período completo</flux:label>
                            <flux:switch wire:model.live="periodo" />
                        </Flux:field>
                    </div>
                    <div class="flex justify-start">
                        <div class="w-42">
                            <flux:label>Días de suscripción:</flux:label>
                        </div>
                        <div class="w-16">
                        <flux:input :disabled="true" maxlength="4" wire:model="cantidadDias" />
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="w-42">
                            <flux:label class="mr-4" wire:model="importe">Comisión suscriptor:</flux:label>
                        </div>
                        {{-- importe--}}
                        <div>
                            <flux:input wire:model="importe" />
                        </div>
                    </div>
                </div>
            </flux:card>

            {{-- buttones--}}
            <div class="flex justify-end pt-4">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>

                </flux:modal.close>

                <flux:button wire:click="grabarContacto()" variant="primary"
                             wireclass="cursor-pointer ms-2">Grabar Suscriptor
                </flux:button>

            </div>
        </form>
        {{-- validaciones--}}
        @if($errors->any())
            <div class="aler aler-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </flux:modal> {{-- The whole world belongs to you. --}}

</div>
