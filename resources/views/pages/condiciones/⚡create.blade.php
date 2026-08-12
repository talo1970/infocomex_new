<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public $modelo;
    public $nombreModelo;
    public $modo;

    public $condicion;
    public $tipo;

    public $nombre;

    #[On('crear-general-modal')]
    public function generalCrear($modelo_name): void
    {
        $this->modelo = "\\App\\Models\\".$modelo_name;

        $this->modo = 'create';
        //dd($this->modelo);

        if ($modelo_name == 'CodigoVenta')
        {
            $this->nombreModelo = "Condición de venta";
        } elseif ( $modelo_name == 'TipoDocumento')
        {
            $this->nombreModelo = "Tipo de Doc.";
        }
    }

    #[On('editar-condicion-modal')]
    public function editarCondicion(\App\Models\CodigoVenta $condicion ): void
    {
        $this->condicion = $condicion;
        $this->modo = 'edit';
        $this->modelo = "\\App\\Models\\CodigoVenta";

        $this->nombre = $this->condicion->nombre;
    }

    #[On('editar-condicion-modal')]
    public function editarTipo(\App\Models\TipoDocumento $Tipo ): void
    {
        $this->tipo = $tipo;
        $this->modo = 'edit';
        $this->modelo = "\\App\\Models\\TipoDocumento";

        $this->nombre = $this->tipo->nombre;
    }

    public function grabarAlta()
    {

        $validated = $this->validate([
                                         'nombre' => ['required', 'string', 'max:150'],
                                     ],
                                     [
                                         'nombre' => 'Se queriere ingresar un Nombre'
                                     ]);

        if ($this->modo == 'create')
        {
            DB::transaction(function()  {
            $general = $this->modelo::create([
                                                'nombre' => $this->nombre,
                                             ]);
            });
        } else {


        }
        Flux::modal('general-modal')->close();
        $this->dispatch('refreshComponent')->to('pages::condiciones.index');
    }



};
?>

<div>
    <flux:modal name="general-modal" class="min-w-[32rem]">
        <form wire:submit="grabarGeneral" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center" size="lg">Alta de {{$this->nombreModelo}}</flux:heading>
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

                <flux:button wire:click="grabarAlta()" variant="primary" wireclass="cursor-pointer ms-2">Grabar Alta</flux:button>

            </div>
        </form>

    </flux:modal>
</div>
