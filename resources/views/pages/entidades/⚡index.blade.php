<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Entidad;
use App\Models\User;
use App\Models\Producto;
use App\Models\TipoEntidad;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public $perPage = 10;

    public $tiposEntidadFilter = 'all';
    public $productosFilter = 'all';
    public $vendedoresFilter = 'all';
    public $tiposOperacionFilter = 'all';

    public $sortBy = "razon_social";
    public $sortDirection = "asc";

    protected $listeners = ['refreshComponent' => '$refresh'];

    #[Computed]
    public function entidades()
    {
        $query = Entidad::query()
                        ->with('tipo_entidad')
                        ->when($this->search, function($query): void {
                            $query->where(function($query):void {
                                $query->where('razon_social', 'like', '%' . $this->search . '%')
                                      ->orWhere('cuit', 'like', '%' . $this->search . '%')
                                      ->orWhere('contacto', 'like', '%' . $this->search . '%')
                                      ->orWhereHas('provincia', function($q) {
                                          $q->where('nombre', 'like', '%' . $this->search . '%');
                                      });
                            });
                        });

        //Filtro por tipos
        $this->aplicarFiltrosTiposEntidad($query);

        //Filtro por productos
        $this->aplicarFiltrosProductos($query);

        //Filtro por tipos de operación
        $this->aplicarFiltrosTiposOperacion($query);

        //Filtro por vendedores
        $this->aplicarFiltrosVendedores($query);

        if (in_array($this->sortBy, ['razon_social', 'cuit', 'contacto'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $query->orderBy('razon_social');
        }

        //dump($query->toSql());
        return $query->latest()->paginate($this->perPage);
    }

    public function aplicarFiltrosTiposEntidad($query)
    {
        if ($this->tiposEntidadFilter !== 'all')
        {
            $query->where('tipo_entidad_id', '=', $this->tiposEntidadFilter);
        }
    }

    public function aplicarFiltrosTiposOperacion($query)
    {
        if ($this->tiposOperacionFilter !== 'all')
        {
            $query->where('tipo_operacion', '=', $this->tiposOperacionFilter);
        }
    }

    public function aplicarFiltrosProductos($query)
    {
        if ($this->productosFilter !== 'all')
        {
            $query->whereHas('entidadeProductoVendedores', function($q) {
                $q->where('producto_id', '=', $this->productosFilter);
            });
        }
    }

    public function aplicarFiltrosVendedores($query)
    {
        if ($this->vendedoresFilter !== 'all')
        {
            $query->whereHas('entidadeProductoVendedores', function($q) {
                $q->where('vendedor_id', '=', $this->vendedoresFilter);
            });
        }
    }

    #[Computed]
    public function tipoEntidad()
    {
        return TipoEntidad::all();
    }

    #[Computed]
    public function productos()
    {
        return Producto::all();
    }

    #[Computed]
    public function vendedores()
    {
        return User::select('id', 'name')->orderby('id')->get();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sort($field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

};
?>

<div class="space-y-4">
    {{-- modal de abm --}}

    {{--<livewire:pages::entidades.form-modal/>--}}

    <div class="relative mb-4 w-full">
        <flux:heading size="xl" level="1">{{ __('Entidades') }}</flux:heading>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Entidades') }}

           {{-- <flux:button size="sm" icon="plus-circle" color="red" variant="primary" class="cursor-pointer">Nuava Entidad</flux:button>--}}
            <a href="{{ route('entidades.create') }}" wire:navigate class="test-blue-600 hover:undesline">
                <flux:badge color="red" inset="top bottom" icon="plus-circle">Nueva Entidad</flux:badge>
            </a>

        </flux:heading>
    </div>

    <div class="flex justify-between items-center flex-wrap gap-2">

        <!.. Search Imput-->
        <flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />


        <div class="flex gap-2">
            <!-- Dropdown por pagina -->
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down"></flux:button>
                <flux:menu>
                    <flux:menu.radio.group wire:model="perPage">
                        @foreach([10, 20, 30, 40, 50] as $size)
                            <flux:menu.radio wire:click="$set('perPage', {{$size}})" value="{{ $size }}">
                                {{ $size }} x Pag.
                            </flux:menu.radio>
                        @endforeach
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
            <!-- Dropdown por producto -->
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down">Productos</flux:button>
                <flux:menu searchable>
                    <flux:menu.radio.group wire:model.live="productosFilter">
                        <flux:menu.radio wire:click="$set('productosFilter', 'all')" value="all" :checked="$productosFilter === 'all'">
                            Todos
                        </flux:menu.radio>
                        @foreach($this->productos as $producto)
                            <flux:menu.radio wire:click="$set('productosFilter', '{{$producto->id}}')" value="{{$producto->id}}" :checked="$productosFilter === '{{$producto->nombre}}'">
                                {{$producto->nombre}}
                            </flux:menu.radio>
                        @endforeach

                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

            <!-- Dropdown por vendedor -->
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down">Vendedores</flux:button>
                <flux:menu searchable>
                    <flux:menu.radio.group wire:model.live="vendedoresFilter">
                        <flux:menu.radio wire:click="$set('vendedoresFilter', 'all')" value="all" :checked="$vendedoresFilter === 'all'">
                            Todos
                        </flux:menu.radio>
                        @foreach($this->vendedores as $vendedor)
                            <flux:menu.radio wire:click="$set('vendedoresFilter', '{{$vendedor->id}}')" value="{{$vendedor->id}}" :checked="$vendedoresFilter === '{{$vendedor->name}}'">
                                {{$vendedor->name}}
                            </flux:menu.radio>
                        @endforeach

                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

            <!-- Dropdown para Tipos Operación de Entidad -->
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down">Tipo Operación Entidad</flux:button>
                <flux:menu searchable>
                    <flux:menu.radio.group wire:model.live="tiposOperacionFilter">
                        <flux:menu.radio wire:click="$set('tiposOperacionFilter', 'all')" value="all" :checked="$tiposOperacionFilter === 'all'">
                            Todos
                        </flux:menu.radio>
                        @foreach(['Importación', 'Exportación'] as $operacion)
                            <flux:menu.radio wire:click="$set('tiposOperacionFilter', '{{$operacion}}')" value="{{$operacion}}" :checked="$tiposOperacionFilter === '{{$operacion}}'">
                                {{$operacion}}
                            </flux:menu.radio>
                        @endforeach

                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

            <!-- Dropdown para Tipos de Entidad -->
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down">Tipos de Entidad</flux:button>
                <flux:menu searchable>
                    <flux:menu.radio.group wire:model.live="tiposEntidadFilter">
                        <flux:menu.radio wire:click="$set('tiposEntidadFilter', 'all')" value="all" :checked="$tiposEntidadFilter === 'all'">
                            Todos
                        </flux:menu.radio>
                        @foreach($this->tipoEntidad as $tipo)
                            <flux:menu.radio wire:click="$set('tiposEntidadFilter', '{{$tipo->id}}')" value="{{$tipo->id}}" :checked="$tiposEntidadFilter === '{{$tipo->nombre}}'">
                                {{$tipo->nombre}}
                            </flux:menu.radio>
                        @endforeach

                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
            <flux:table.column sorteable :sorted="$sortBy === 'tipo_entidad_id'" :direction="$sortDirection" wire:click="sort('tipo_entidad_id')">Tipo</flux:table.column>
            <flux:table.column sorteable :sorted="$sortBy === 'razon_social'" :direction="$sortDirection" wire:click="sort('razon_social')">Razón Social</flux:table.column>
            <flux:table.column>teléfono</flux:table.column>
            <flux:table.column sorteable :sorted="$sortBy === 'contacto'" :direction="$sortDirection" wire:click="sort('contacto')">Contacto</flux:table.column>
            <flux:table.column>Provincia</flux:table.column>
            <flux:table.column align="center">Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->entidades as $entidad)

                <flux:table.row :key="$entidad->id">
                    <flux:table.cell>
                        <flux:badge color="{{ $entidad->tipo_entidad->color }}" as="button">
                            {{ $entidad->tipo_entidad->nombre }}
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $entidad->razon_social }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $entidad->telefono }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $entidad->contacto }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $entidad->provincia->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:tooltip content="Editar Entidad">
                            {{--<a href="{{ route('entidades.edit', $entidad) }}" target="_blank" wire:navigate class="test-blue-600 hover:undesline">--}}
                            <a href="{{ route('entidades.edit', $entidad) }}" wire:navigate class="test-blue-600 hover:undesline">
                                <flux:badge color="indigo" inset="top bottom" icon="pencil"></flux:badge>
                            </a>
                        </flux:tooltip>

                        {{--<flux:modal.trigger name="entidad-form-modal">

                            <flux:tooltip content="Modificar">
                                <flux:badge color="amber" as="button"
                                            wire:click="$dispatch('open-entidad-modal', { modo: 'edit', entidad:{{ $entidad->id }} })"
                                            class="cursor-pointer mx-2" icon="pencil">
                                </flux:badge>
                            </flux:tooltip>

                        </flux:modal.trigger>
--}}
                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>

    {{ $this->entidades->links() }}

</div>
