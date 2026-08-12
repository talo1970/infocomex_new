<?php

use Livewire\Component;

new class extends Component
{
    public \App\Models\Entidad $entidad;

    public string $search = '';
    public $perPage = 10;
    public $sortBy = "contacto";
    public $sortDirection = "asc";

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $tipoentidadid;

    public $id;

    public $contactos= [];

    public function mount(\App\Models\Entidad $entidad ): void
    {
        $this->entidad = $entidad;
        $this->id = $entidad->id;
    }


};
?>

<div>
    <livewire:pages::contactos.crear/>
    <livewire:pages::contactos.show/>
    <livewire:pages::contactos.edit/>

    <div class="my-2 mx-2 flex justify-between items-center flex-wrap gap-2">

        <!-- Search Imput-->
        <flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />
        <!-- modal-->
        <flux:modal.trigger name="contacto-modal">
            <flux:button
                wire:click="$dispatch('crear-contacto-modal', { modo: 'crear', entidad: {{$entidad->id}}})"
                size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nuevo Contacto</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
            <flux:table.column>Contacto</flux:table.column>
            <flux:table.column>E-mail</flux:table.column>
            <flux:table.column>Teléfono</flux:table.column>
            <flux:table.column>Provincia</flux:table.column>
            <flux:table.column align="center">Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->entidad->contactos as $contacto)

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
    {{--soy los contactos {{ $this->razon_social }} {{ $this->entidad->contactos()->count() }}<br>

    @foreach ($this->entidad->contactos as $contacto)

                    {{ $contacto->contacto }} <br>
    @endforeach
--}}

</div>
