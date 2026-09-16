<?php

    use App\Models\User;
    use Livewire\Attributes\Computed;
    use Livewire\Attributes\Url;
    use Livewire\Component;
    use Livewire\WithPagination;


    new class extends Component {

        use WithPagination;

        public        $rolFilters = 'all';
        public string $search     = '';
        public        $perPage    = 10;
        public $sortBy        = "name";
        public $sortDirection = "asc";

        protected $listeners = [ 'refreshComponent' => '$refresh' ];

        #[Computed]
        public function usuarios()
        {
            //$this->usuarios = User::select('id', 'name', 'cuil', 'email', 'rol_id')->with('rol')->get();
            $query = \App\Models\user::query()->when($this->search, function($query): void {
                    $query->where(function($query): void {
                        $query->where('name', 'like', "%{$this->search}%")
                              ->orderBy($this->sortBy, $this->sortDirection);
                    });
                });
            //Filtros de rol
            $this->applyRolFilters($query);

            //dd($query->toSql());

            if (in_array($this->sortBy, [ 'name' ])) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            }
            else {
                $query->orderBy('name', 'desc'); // Orden por defecto
            }

            return $query->latest()->paginate($this->perPage);
        }

        private function applyRolFilters($query)
        {
            if ($this->rolFilters !== 'all') {
                $query->where('rol_id', '=', $this->rolFilters);
                //dd($query->toSql());
            }
        }

        public function updatedSearch(): void
        {
            $this->resetPage();
        }


    };
?>

<div class="space-y-4">
    <div class="-mb-4">
        <livewire:pages::admin.usuarios.show />
        <livewire:pages::admin.usuarios.edit />
        <livewire:pages::admin.usuarios.permisos />
    </div>
    <div class="relative mb-4 w-full bg-gradient-to-r from-indigo-50 to-gray-300">
        <flux:heading size="xl" level="1" class="ml-2">
            {{ __('Usuarios') }}
        </flux:heading>

        <flux:subheading size="lg" class="mb-4 ml-2 flex justify-between">{{ __('Administración de Usuarios') }}
            {{--@if(auth()->user()->hasPermissionTo('judicial.crear', 'web'))
            @can('judicial.crear')
                <a href="{{ route('judicial.create') }}" wire:navigate class="test-blue-600 hover:undesline">
                    <flux:button size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nueva Causadd</flux:button>
                </a>
            --}}
            {{--@endif
        @endcan
        --}}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex justify-between items-center flex-wrap gap-2">
        <!-- Search Input -->
        <flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />


    </div>


    <flux:table>
        <flux:table.columns class="bg-indigo-100 text-center">
            <flux:table.column>&ensp;Nombre</flux:table.column>
            <flux:table.column>Correo Electrónico</flux:table.column>
            <flux:table.column>CUIL</flux:table.column>
            <flux:table.column>Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach($this->usuarios as $usuario)
                <flux:table.row :key="$usuario->id" class="hover:bg-gray-100">

                    <flux:table.cell class="m-8 ">
                        &ensp;{{$usuario->name}}
                    </flux:table.cell>

                    <flux:table.cell class="ml-16">
                        {{$usuario->email}}
                    </flux:table.cell>

                    <flux:table.cell class="text-center w-32">
                        {{App\Helpers\CuitHelper::format($usuario->cuil)}}
                    </flux:table.cell>

                    <flux:table.cell class="text-center w-24">
                        <flux:modal.trigger name="show-usuario-modal">
                            <flux:tooltip content="Consulta Usuario">
                                <flux:badge color="sky" as="button"
                                            wire:click="$dispatch('mostrarUsuario', { user: '{{$usuario->id}}' })"
                                            icon="eye" class="cursor-pointer">
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>

                        <flux:modal.trigger name="edit-usuario-modal">
                            <flux:tooltip content="Editar Usuario">
                                <flux:badge color="indigo" as="button"
                                            wire:click="$dispatch('editarUsuario', { user: '{{$usuario->id}}'})"
                                            icon="pencil" class="cursor-pointer">
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>

                        <flux:modal.trigger name="permisos-usuario-modal">
                            <flux:tooltip content="Permisos del Usuario">
                                <flux:badge color="red" as="button"
                                            wire:click="$dispatch('permisosUsuario', { user: '{{$usuario->id}}'})"
                                            icon="key" class="cursor-pointer">
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>&ensp;

                    </flux:table.cell>

                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    {{ $this->usuarios->links() }}

</div>
