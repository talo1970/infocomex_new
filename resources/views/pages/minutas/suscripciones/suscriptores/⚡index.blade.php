<?php

use Livewire\Component;

new class extends Component
{
    //public $suscritores= [];
    public array $suscritores_tmp = [];

    protected $listeners = [
        'subirDocumento',
        'uploadedArchivo',
    ];


    public function uploadedArchivo($archivo)
    {
        $this->archivos_tmp[] = $archivo;
        $this->CantArchivos   = count($this->archivos_tmp);
    }



};
?>

<div>
    <div class="my-2 mx-2 flex justify-end items-center flex-wrap gap-2">

        <!-- modal-->
        <flux:modal.trigger name="contacto-modal">
            <flux:button
                wire:click="$dispatch('crear-contacto-modal', { modo: 'crear', entidad: {{'__ver entidad'}}})"
                size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Suscriptor</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="h-4 bg-indigo-100 dark:bg-blue-400 text-blue-600">
            <flux:table.column>Suscritor</flux:table.column>
            <flux:table.column>Inicial</flux:table.column>
            <flux:table.column>Vencimiento</flux:table.column>
            <flux:table.column>Dias</flux:table.column>
            <flux:table.column align="center">Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->suscritores as $contacto)

                <flux:table.row :key="$contacto->id">
                    <flux:table.cell>
                        {{ $contacto->contacto }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $contacto->mail }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $contacto->telefono }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $contacto->provincia->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:modal.trigger name="contacto-show-modal">
                            <flux:tooltip content="Consulta Contacto">
                                <flux:badge color="sky" as="button"
                                            wire:click="$dispatch('show-contacto-modal', { modo: 'show', contacto: {{$contacto}}})"
                                            icon="eye" class="cursor-pointer" >
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>

                        <flux:modal.trigger name="contacto-edit-modal">
                            <flux:tooltip content="Editar Contacto">
                                <flux:badge color="indigo" as="button"
                                            wire:click="$dispatch('edit-contacto-modal', { modo: 'edit', contacto: {{$contacto}}})"
                                            icon="pencil" class="cursor-pointer" >
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>


                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>
</div>
