<?php

    use Livewire\Attributes\On;
    use Livewire\Component;

    new class extends Component {
        public $dias = 0;

        public $inicio;
        public $fin;

        #[On('crear-suscriptor')]
        public function crearSuscriptorr($minuta, $vencimiento): void
        {
            $this->entidad_id = $minuta;
            //$this->fin =$vto;

            $this->fin  = \Carbon\Carbon::parse($vencimiento)->format('Y-m-d');

           dd($this->entidad_id.' - '.$this->fin);


        }

        /*
        public salvarSuscriptor()
        {
            // tener idea de grabar suscriptor sin tener la minuta

            $archivoAttributes = [
            'id'        => null,
            'nombre'    => $this->archivo->getClientOriginalName(),
            'inicio'      => $this->archivo->store('mensajes-archivos', 'public'),
            'vencimiento' => $this->archivo->getClientOriginalExtension(),
            'dias'    => $this->archivo->getClientOriginalName(),
            'importe'    => $this->archivo->getClientOriginalName(),
            'periodo'    => $this->archivo->getClientOriginalName(),
            ];

            una ves que lo quiere grabar va a index de suscriptores para actualizar la tabla
                    $this->dispatch('uploadedArchivo', archivo: $archivoAttributes)->to(CrearMensaje::class);

        }
        */


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
                        <flux:date-picker type="input" label="Fecha inicio" wire:model="inicio" />
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
                            <flux:switch wire:model.liv="peridod" />
                        </Flux:field>
                    </div>
                    <div>
                        <Flux:field variant="inline">
                            <flux:label class="mr-4 bg-red-100">Días de suscripción:</flux:label>
                            <flux:label>{{$this->dias}} </flux:label>
                        </Flux:field>
                    </div>
                    <div class="flex justify-between">
                        <div class="w-42">
                            <flux:label class="mr-4">Comisión suscriptor:</flux:label>
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
