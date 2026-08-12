<?php

use Livewire\Component;
use Livewire\Attributes\On;


new class extends Component
{

    public $modo;
    public $nombre;
    public $simbolo;
    public $pais;

    public $moneda;

    #[On('moneda-crear')]
    public function crearMoneda($modo): void
    {
        $this->modo = $modo;

    }

    #[On('moneda-editar')]
    public function editarMoneda($modo, \App\Models\Moneda $moneda): void
    {
        $this->modo = $modo;
        $this->moneda = $moneda;

        $this->nombre = $this->moneda->nombre;
        $this->simbolo = $this->moneda->simbolo;
        $this->pais = $this->moneda->pais;

    }

    public function grabarMoneda()
    {
        $validated = $this->validate([
                                         'nombre' => ['required', 'string', 'max:150'],
                                         'simbolo' => ['required', 'string', 'max:10'],
                                         'pais' => ['required', 'string', 'max:150'],
                                     ],
                                     [
                                         'nombre' => 'Se queriere ingresar un Nombre',
                                         'simbolo' => 'Se queriere ingresar un Símbolo',
                                         'pais' => 'Se queriere ingresar a que pais pertenece',
                                     ]);

        if ($this->modo == 'Alta')
        {
            DB::transaction(function()  {
                $referencia = \App\Models\Moneda::create([
                                                                 'nombre' => $this->nombre,
                                                                 'simbolo' => $this->simbolo,
                                                                 'pais' => $this->pais,
                                                             ]);
            });
        } else {
            $this->moneda->nombre = $this->nombre;
            $this->moneda->simbolo = $this->simbolo;
            $this->moneda->pais = $this->pais;
            $this->moneda->save();
        }

        Flux::modal('moneda-modal')->close();
        $this->dispatch('refreshComponent')->to('pages::monedas.index');
    }

};
?>

<div>
    <flux:modal name="moneda-modal" class="min-w-[32rem]">
        <div>
            <flux:heading class="font-bold bg-red-100 text-center" size="lg">{{$this->modo}} - Moneda</flux:heading>
        </div>

        <flux:card>
            {{-- 1° fila --}}
            <div class="flex w-full flex-row items-start space-x-4 text-left">
                {{-- Nombre --}}
                <div class="w-1/2">
                    <flux:input wire:model="nombre" label="Nombre" placeholder="Ingrese un nombre" />
                </div>
                {{-- Símbolo --}}
                <div class="w-1/4">
                    <flux:input wire:model="simbolo" label="Símbolo" placeholder="Ingrese un simbolo" />
                </div>
                {{-- Nombre --}}
                <div class="w-1/4">
                    <flux:input wire:model="pais" label="Pais" placeholder="Ingrese un pais" />
                </div>
            </div>
        </flux:card>

        {{-- buttones--}}
        <div class="flex justify-end pt-4">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>

            </flux:modal.close>

            <flux:button wire:click="grabarMoneda()" variant="primary" wireclass="cursor-pointer ms-2">Grabar</flux:button>

        </div>

    </flux:modal>

</div>
