<?php

use Livewire\Component;
use Livewire\Attributes\On;


new class extends Component
{
    public $modo;
    public $nombre;
    public $referencia;

    #[On('referencia-crear')]
    public function crearReferencia($modo): void
    {
        $this->modo = $modo;

    }

    #[On('referencia-editar')]
    public function editarReferencia($modo, \App\Models\Referencia $referencia): void
    {
        $this->modo = $modo;
        $this->referencia = $referencia;


    }

    public function grabarReferencia()
    {
        $validated = $this->validate([
                                         'nombre' => ['required', 'string', 'max:150'],
                                     ],
                                     [
                                         'nombre' => 'Se queriere ingresar un Nombre'
                                     ]);

        if ($this->modo == 'Alta')
        {
            DB::transaction(function()  {
                $referencia = \App\Models\Referencia::create([
                                                     'nombre' => $this->nombre,
                                                 ]);
            });
        } else {
            $this->referencia->nombre = $this->nombre;
            $this->referencia->save();

        }
        Flux::modal('referencia-modal')->close();
        $this->dispatch('refreshComponent')->to('pages::referencias.index');
    }

};
?>

<div>
    <flux:modal name="referencia-modal" class="min-w-[32rem]">
        <div>
            <flux:heading class="font-bold bg-red-100 text-center" size="lg">{{$this->modo}} - Referencia</flux:heading>
        </div>

        <flux:card>
            {{-- 1° fila --}}
            <div class="flex w-full flex-row items-start space-x-4 text-left">
                {{-- Nombre --}}
                <div class="w-full">
                    <flux:input wire:model="nombre" label="Nombre" placeholder="Ingrese un nombre" />
                </div>
            </div>
        </flux:card>

        {{-- buttones--}}
        <div class="flex justify-end pt-4">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>

            </flux:modal.close>

            <flux:button wire:click="grabarReferencia()" variant="primary" wireclass="cursor-pointer ms-2">Grabar</flux:button>

        </div>

    </flux:modal>

</div>
