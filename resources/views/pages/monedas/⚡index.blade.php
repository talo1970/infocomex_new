<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public $perPage = 10;

    public $sortBy = "nombre";
    public $sortDirection = "asc";

    protected $listeners = ['refreshComponent' => '$refresh'];

    #[Computed]
    public function monedas()
    {
        $query = \App\Models\Moneda::query()
                                       ->when($this->search, function($query): void {
                                           $query->where(function($query):void {
                                               $query->where('nombre', 'like', '%' . $this->search . '%');
                                           });
                                       });

        if (in_array($this->sortBy, ['nombre', 'simbolo', 'pais', 'seleccion'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $query->orderBy('nombre');
        }

        //dump($query->toSql());
        return $query->latest()->paginate($this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sort($field): void
    {
        //dump($field);
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // cambio en selección en Valores
    public function selec($value)
    {
        $unSelec = \App\Models\Moneda::find($value);
        if ($unSelec->seleccion == false) {
            $seleccionado            = \App\Models\Moneda::where('seleccion', true)->first();
            $seleccionado->seleccion = false;
            $seleccionado->save();
            $unSelec->seleccion = true;
            $unSelec->save();
            $this->monedas();
        }
    }


};
?>

<div>
    <livewire:pages::monedas.amb/>

    <div class="space-y-4">
        <div class="relative mb-4 w-full">
            <flux:heading size="xl" level="1">{{ __('Monedas') }}</flux:heading>
            <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Monedas') }}

            </flux:heading>
        </div>
        <div class="flex justify-between items-center flex-wrap gap-2">

        <!.. Search Imput-->
        <flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />
        <!-- modal-->
        <flux:modal.trigger name="moneda-modal">
            <flux:button
                wire:click="$dispatch('moneda-crear', { modo: 'Alta'})"
                size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nueva Moneda</flux:button>
        </flux:modal.trigger>
    </div>

        <flux:table class="max-w-9/10" >
            <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
                <flux:table.column sorteable :sorted="$sortBy === 'nombre'" :direction="$sortDirection" wire:click="sort('nombre')" class="w-[32rem] cursor-pointer">&emsp;Nombre</flux:table.column>
                <flux:table.column sorteable :sorted="$sortBy === 'simbolo'" :direction="$sortDirection" wire:click="sort('simbolo')"class="cursor-pointer">Símbolo</flux:table.column>
                <flux:table.column sorteable :sorted="$sortBy === 'pais'" :direction="$sortDirection" wire:click="sort('pais')"class="cursor-pointer">Pais</flux:table.column>
                <flux:table.column sorteable :sorted="$sortBy === 'seleccion'" :direction="$sortDirection" wire:click="sort('seleccion')"class="cursor-pointer">Favorito</flux:table.column>
                <flux:table.column align="center">Acción</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->monedas as $moneda)

                    <flux:table.row :key="$moneda->id">
                        <flux:table.cell>
                            {{ $moneda->nombre }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $moneda->simbolo }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $moneda->pais }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge rounded wire:click="selec({{$moneda->id}})" class="cursor-pointer mx-2" >
                                @if($moneda->seleccion)
                                    {{--<flux:switch  checked />--}}
                                    <flux:icon.check-circle variant="solid" class="text-green-600 "/>
                                @else
                                    {{--<flux:switch />--}}
                                    <flux:icon.x-circle class="text-red-700"/>
                                @endif
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:modal.trigger name="moneda-modal">
                                <flux:tooltip content="Editar Moneda">
                                    <flux:badge color="indigo" as="button"
                                                wire:click="$dispatch('moneda-editar', { modo: 'Editar', moneda: {{$moneda}}})"
                                                icon="pencil" class="cursor-pointer" >
                                    </flux:badge>
                                </flux:tooltip>
                            </flux:modal.trigger>

                        </flux:table.cell>

                    </flux:table.row>
                @endforeach

            </flux:table.rows>
        </flux:table>
    {{ $this->monedas->links() }}
   </div>
</div>
