<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public $modelo;
    public $codigo;
    public $tipo;
    public $nombre;


    #[On('editar-codigo-modal')]
    public function editarCodigo( \App\Models\CodigoVenta $codigo ): void
    {
        $this->codigo = $codigo;
        $this->modo = 'edit';
        $this->modelo = "CodigoVenta";

        $this->nombre = $this->codigo->nombre;
    }

    #[On('editar-general-modal')]
    public function editarTipo(\App\Models\TipoDocumento $tipo ): void
    {
        $this->tipo = $tipo;
        $this->modo = 'edit';
        $this->modelo = "TipoDocumento";

        $this->nombre = $this->tipo->nombre;

    }

    public function grabarEdicion()
    {

        $validated = $this->validate([
                                         'nombre' => ['required', 'string', 'max:150'],
                                     ],
                                     [
                                         'nombre' => 'Se queriere ingresar un Nombre'
                                     ]);


        if ($this->modelo == "TipoDocumento")
        {
            $this->tipo->nombre = $this->nombre;
            $this->tipo->save();

        } elseif ($this->modelo == "CodigoVenta")
        {
            $this->codigo->nombre = $this->nombre;
            $this->codigo->save();
        }

        Flux::modal('general-edit-modal')->close();
        $this->dispatch('refreshComponent')->to('pages::condiciones.index');


    }


};
?>

<div>
    <flux:modal name="general-edit-modal" class="min-w-[32rem]">
        <form wire:submit="grabarGeneral" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-700" size="lg">Editar</flux:heading>
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

                <flux:button wire:click="grabarEdicion()" variant="primary" wireclass="cursor-pointer ms-2">Grabar</flux:button>

            </div>
        </form>

    </flux:modal>

</div>
