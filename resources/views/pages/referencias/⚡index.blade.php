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
    public function referencias()
    {
        $query = \App\Models\Referencia::query()
                        ->when($this->search, function($query): void {
                            $query->where(function($query):void {
                                $query->where('nombre', 'like', '%' . $this->search . '%');
                            });
                        });

        if (in_array($this->sortBy, ['nombre', 'seleccion'])) {
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
        $unSelec = \App\Models\Referencia::find($value);
        if ($unSelec->seleccion == false) {
            $seleccionado            = \App\Models\Referencia::where('seleccion', true)->first();
            $seleccionado->seleccion = false;
            $seleccionado->save();
            $unSelec->seleccion = true;
            $unSelec->save();
            $this->referencias();
        }
    }

};
?>

<div class="space-y-4">

    <livewire:pages::referencias.amb/>

    <div class="relative mb-4 w-full">
        <flux:heading size="xl" level="1">{{ __('Referencia') }}</flux:heading>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Referencias') }}

        </flux:heading>
    </div>
    <div class="flex justify-between items-center flex-wrap gap-2">

        <!.. Search Imput-->
        <flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />
        <!-- modal-->
        <flux:modal.trigger name="referencia-modal">
            <flux:button
                wire:click="$dispatch('referencia-crear', { modo: 'Alta'})"
                size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Nueva Referencia</flux:button>
        </flux:modal.trigger>

    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
            <flux:table.column sorteable :sorted="$sortBy === 'nombre'" :direction="$sortDirection" wire:click="sort('nombre')" class="w-[72rem] cursor-pointer">&emsp;Nombre</flux:table.column>
            <flux:table.column sorteable :sorted="$sortBy === 'seleccion'" :direction="$sortDirection" wire:click="sort('seleccion')"class="cursor-pointer">Favorito</flux:table.column>
            <flux:table.column align="center">Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->referencias as $referencia)

                <flux:table.row :key="$referencia->id">
                    <flux:table.cell>
                        {{ $referencia->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:badge rounded wire:click="selec({{$referencia->id}})" class="cursor-pointer mx-2" >
                            @if($referencia->seleccion)
                                {{--<flux:switch  checked />     size-4  --}}
                                <flux:icon.check-circle variant="solid" class="text-green-600"/>
                            @else
                                {{--<flux:switch />--}}
                                <flux:icon.x-circle class="text-red-700 size-4"/>
                            @endif
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:modal.trigger name="referencia-modal">
                            <flux:tooltip content="Editar Referencia">
                                <flux:badge color="indigo" as="button"
                                            wire:click="$dispatch('referencia-editar', { modo: 'Editar', referencia: {{$referencia}}})"
                                            icon="pencil" class="cursor-pointer" >
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>

                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>
    {{ $this->referencias->links() }}
</div>
