<?php

use Livewire\Component;

new class extends Component
{
    public $roles;

    public function mount(): void
    {
        $this->roles = \Spatie\Permission\Models\Role::withCount('permissions')->get();

    }

};
?>

<div class="space-y-4">
    <div class="-mb-4">

    <livewire:pages::admin.roles.edit/>
    </div>

    <div class="relative mb-4 w-full bg-gradient-to-r from-indigo-50 to-gray-300">
            <flux:heading size="xl" level="1" class="ml-2">
                {{ __('Roles') }}
            </flux:heading>

        <flux:subheading size="lg" class="mb-4 ml-2 flex justify-between">{{ __('Administración de Roles') }}
            {{--@if(auth()->user()->hasPermissionTo('judicial.crear', 'web'))
            @can('judicial.crear')
                <a href="{{ route('judicial.create') }}" wire:navigate class="test-blue-600 hover:undesline">
                    <flux:button size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nueva Causadd</flux:button>
                </a>
                {{--@endif
            @endcan
            --}}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:table>
        <flux:table.columns class="bg-indigo-100 text-center">
            <flux:table.column>&ensp;Nombre</flux:table.column>
            <flux:table.column>Guard</flux:table.column>
            <flux:table.column># Permisos</flux:table.column>
            <flux:table.column>Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach($this->roles as $role)
                <flux:table.row :key="$role->id" class="hover:bg-gray-100">

                    <flux:table.cell class="m-8">
                        &ensp;{{$role->name}}
                    </flux:table.cell>

                    <flux:table.cell class="ml-16">
                        {{$role->guard_name}}
                    </flux:table.cell>

                    <flux:table.cell class="text-center w-32">
                        {{$role->permissions_count}}
                    </flux:table.cell>

                    <flux:table.cell class="text-center w-24">
                        <flux:modal.trigger name="edit-roles-modal">
                            <flux:tooltip content="Editar">
                                <flux:badge color="indigo" as="button"
                                            wire:click="$dispatch('editar-rol-modal', { rol: '{{$role->id}}'})"
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
