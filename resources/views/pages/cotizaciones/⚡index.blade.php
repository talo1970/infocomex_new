<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Number;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public $monedasFilter = 'all';

    public $sortBy = "nombre";
    public $sortDirection = "asc";

    protected $listeners = ['refreshComponent' => '$refresh'];

    #[Computed]
    public function cotizaciones()
    {
        $query = \App\Models\Cotizacion::query()
                                   ->when($this->search, function($query): void {
                                       $query->where(function($query):void {
                                           $query->where('fecha', 'like', '%' . $this->search . '%');
                                       });
                                   });

        //Filtro por tipos
        $this->aplicarFiltrosMonedas($query);

        //dump($query->toSql());
        if (in_array($this->sortBy, ['fecha'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $query->orderByDesc('fecha');
        }

        return $query->latest()->paginate($this->perPage);
    }

    public function aplicarFiltrosMonedas($query)
    {
        if ($this->monedasFilter !== 'all')
        {
            $query->where('moneda_id', '=', $this->monedasFilter);
        }
    }

    #[Computed]
    public function monedas()
    {
        return \App\Models\Moneda::select('id', 'nombre')->get();
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

    <livewire:pages::cotizaciones.abm/>

    <div class="relative mb-4 w-full">
        <flux:heading size="xl" level="1">{{ __('Monedas') }}</flux:heading>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Monedas') }}

            <!-- modal-->
            <flux:modal.trigger name="cotizacion-modal">
                <flux:button
                    wire:click="$dispatch('cotizacion-crear', { modo: 'Alta'})"
                    size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nueva Cotización</flux:button>
            </flux:modal.trigger>
        </flux:heading>
    </div>
    <div class="flex justify-between items-center flex-wrap gap-2">

        <!.. Search Imput-->
        <flux:input icon="magnifying-glass" wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />

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

            <!-- Dropdown para Tipos de Entidad -->
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down">Monedas</flux:button>
                <flux:menu searchable>
                    <flux:menu.radio.group wire:model.live="monedasFilter">
                        <flux:menu.radio wire:click="$set('monedasFilter', 'all')" value="all" :checked="$monedasFilter === 'all'">
                            Todos
                        </flux:menu.radio>
                        @foreach($this->monedas as $moneda)
                            <flux:menu.radio wire:click="$set('monedasFilter', '{{$moneda->id}}')" value="{{$moneda->id}}" :checked="$monedasFilter === '{{$moneda->nombre}}'">
                                {{$moneda->nombre}}
                            </flux:menu.radio>
                        @endforeach

                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        </div>



    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
            <flux:table.column sorteable :sorted="$sortBy === 'fecha'" :direction="$sortDirection" wire:click="sort('fecha')" class="w-[32rem] cursor-pointer">&emsp;Fecha</flux:table.column>
            <flux:table.column sorteable :sorted="$sortBy === 'moneda'" :direction="$sortDirection" wire:click="sort('moneda')"class="cursor-pointer">Moneda</flux:table.column>
            <flux:table.column sorteable :sorted="$sortBy === 'cotizacion'" :direction="$sortDirection" wire:click="sort('cotizacion')" class="cursor-pointer w-32">Cotización $</flux:table.column>
            <flux:table.column align="center">Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->cotizaciones as $cotizacion)

                <flux:table.row :key="$cotizacion->id">
                    <flux:table.cell>
                        {{Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $cotizacion->moneda->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="text-right">
                            {{ Number::format($cotizacion->cotizacion, locale: 'es')}}
                            {{-- Number::currency($cotizacion->cotizacion, locale: 'en')--}}

                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="text-center">
                            <flux:modal.trigger name="cotizacion-modal">
                                <flux:tooltip content="Editar cotización">
                                    <flux:badge color="indigo" as="button"
                                                wire:click="$dispatch('cotizacion-editar', { modo: 'Editar', cotizacion: {{$cotizacion}}})"
                                                icon="pencil" class="cursor-pointer" >
                                    </flux:badge>
                                </flux:tooltip>
                            </flux:modal.trigger>
                        </div>

                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>
    {{ $this->cotizaciones->links() }}

</div>
