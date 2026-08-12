<?php

use Livewire\Component;
use Livewire\Attributes\Computed;



new class extends Component
{

    public string $search = '';
    public $perPage = 20;

    public $sortBy = "nombre";
    public $sortDirection = "asc";

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $seleccion;
    public $select =4;

    //codigo de venta
    #[Computed]
    public function codigos()
    {
        $query = \App\Models\CodigoVenta::query()
                        ->when($this->search, function($query): void {
                            $query->where(function($query):void {
                                $query->where('nombre', 'like', '%' . $this->search . '%');
                            });
                        });

        if (in_array($this->sortBy, ['nombre'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $query->orderBy('nombre');
        }

        //dd($query->toSql());
        return $query->latest()->paginate($this->perPage);
    }

    public function updatedSelect(): void
    {
        dump($this->select);
    }

    // cambio en slección código
    public function selec($value)
    {
        $unSelec = \App\Models\CodigoVenta::find($value);
        if ($unSelec->seleccion == false)
        {
            $seleccionado = \App\Models\CodigoVenta::where('seleccion', true)->first();
            $seleccionado->seleccion = false;
            $seleccionado->save();
            $unSelec->seleccion = true;
            $unSelec->save();
            $this->codigos();
        }
    }

    // tipos de documentos
    #[Computed]
    public function tipos()
    {
        $query = \App\Models\TipoDocumento::query()
                                        ->when($this->search, function($query): void {
                                            $query->where(function($query):void {
                                                $query->where('nombre', 'like', '%' . $this->search . '%');
                                            });
                                        });

        if (in_array($this->sortBy, ['nombre'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $query->orderBy('nombre');
        }

        //dd($query->toSql());
        return $query->latest()->paginate($this->perPage);
    }

    // cambio en seleción tipo
    public function selecTipo($value)
    {
        $unSelec = \App\Models\TipoDocumento::find($value);
        if ($unSelec->seleccion == false)
        {
            $seleccionado = \App\Models\TipoDocumento::where('seleccion', true)->first();
            $seleccionado->seleccion = false;
            $seleccionado->save();
            $unSelec->seleccion = true;
            $unSelec->save();
            $this->tipos();
        }
    }

    //Valores
    #[Computed]
    public function valores()
    {
        $query = \App\Models\Valor::query()
                                          ->when($this->search, function($query): void {
                                              $query->where(function($query):void {
                                                  $query->where('nombre', 'like', '%' . $this->search . '%');
                                              });
                                          });

        if (in_array($this->sortBy, ['nombre'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $query->orderBy('nombre');
        }

        //dd($query->toSql());
        return $query->latest()->paginate($this->perPage);
    }

    // cambio en selección en Valores
    public function selecValor($value)
    {
        $unSelec = \App\Models\Valor::find($value);
        if ($unSelec->seleccion == false)
        {
            $seleccionado = \App\Models\Valor::where('seleccion', true)->first();
            $seleccionado->seleccion = false;
            $seleccionado->save();
            $unSelec->seleccion = true;
            $unSelec->save();
            $this->valores();
        }
    }

};
?>

<div>

    <livewire:pages::condiciones.create/>
    <livewire:pages::condiciones.edit/>

    <div class="mt-0 flex w-full flex-row items-start space-x-4 text-left">
        <div class="w-1/2 ">
            <div class="relative mb-4 w-full">
                <flux:heading size="xl" level="1">{{ __('Condición Venta') }}</flux:heading>
                <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Condición de venta') }}

                    <!-- modal-->
                    <flux:modal.trigger name="general-modal">
                        <flux:badge
                            wire:click="$dispatch('crear-general-modal', { modelo_name: 'CodigoVenta'})"
                            icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Condición Vta.
                        </flux:badge>
                    </flux:modal.trigger>



                </flux:heading>
            </div>
            <div class="flex justify-between items-center flex-wrap gap-2">

                <!-- Search Imput-->
                <!--<flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />-->

                {{--<div class="flex gap-2">
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

                </div>--}}
            </div>


            <flux:table class="max-w-9/10" >
                <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
                    <flux:table.column sorteable :sorted="$sortBy === 'nombre'" :direction="$sortDirection" wire:click="sort('nombre')">Nombre</flux:table.column>
                    <flux:table.column>favorito</flux:table.column>
                    <flux:table.column align="center">Acción</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>

                    @foreach ($this->codigos as $codigo)

                        <flux:table.row :key="$codigo->id">
                            <flux:table.cell>
                                    {{ $codigo->nombre }}
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:badge rounded wire:click="selec({{$codigo->id}})" class="cursor-pointer mx-2" >
                                @if($codigo->seleccion)
                                    {{--<flux:switch  checked />--}}
                                    <flux:icon.check-circle variant="solid" class="text-green-600"/>
                                @else
                                    {{--<flux:switch />--}}
                                    <flux:icon.x-circle class="text-red-700"  />
                                @endif
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:modal.trigger name="general-edit-modal">
                                    <flux:tooltip content="Editar Tipo Doc.">
                                        <flux:badge color="indigo" as="button"
                                                    wire:click="$dispatch('editar-codigo-modal', { modelo_name: 'CodigoVenta', codigo: {{$codigo}}})"
                                                    icon="pencil" class="cursor-pointer" >
                                        </flux:badge>
                                    </flux:tooltip>
                                </flux:modal.trigger>

                            </flux:table.cell>

                        </flux:table.row>
                    @endforeach

                </flux:table.rows>
            </flux:table>
            {{-- $this->codigos->links() --}}
        </div>

        <div class="w-1/2 ">
            <div class="relative mb-4 w-full">
                <flux:heading size="xl" level="1">{{ __('Tipos de Documentos') }}</flux:heading>
                <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Tipos de Documentos') }}

                    <!-- modal-->
                    <flux:modal.trigger name="general-modal">
                        <flux:badge
                            wire:click="$dispatch('crear-general-modal', { modelo_name: 'TipoDocumento'})"
                             icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Tipo de Doc.
                        </flux:badge>
                    </flux:modal.trigger>
                </flux:heading>
            </div>
            <div class="flex justify-between items-center flex-wrap gap-2">

                <!-- Search Imput -->
                <!--<flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />-->

                {{--<div class="flex gap-2">
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
                </div>--}}
            </div>

            <flux:table class="max-w-9/10" >
                <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
                    <flux:table.column sorteable :sorted="$sortBy === 'nombre'" :direction="$sortDirection" wire:click="sort('nombre')">Nombre</flux:table.column>
                    <flux:table.column>favorito</flux:table.column>
                    <flux:table.column align="center">Acción</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->tipos as $tipo)
                        <flux:table.row :key="$tipo->id">
                            <flux:table.cell>
                                {{ $tipo->nombre }}
                            </flux:table.cell>

                            <flux:table.cell>

                                <flux:badge rounded wire:click="selecTipo({{$tipo->id}})" class="cursor-pointer mx-2" >
                                    @if($tipo->seleccion)
                                        {{--<flux:switch  checked />--}}
                                        <flux:icon.check-circle variant="solid" class="text-green-600"/>
                                    @else
                                        {{--<flux:switch />--}}
                                        <flux:icon.x-circle class="text-red-700"  />
                                    @endif
                                </flux:badge>

                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:modal.trigger name="general-edit-modal">
                                    <flux:tooltip content="Editar Tipo Doc.">
                                        <flux:badge color="indigo" as="button"
                                                    wire:click="$dispatch('editar-general-modal', { modelo_name: 'TipoDocumento', tipo: {{$tipo}}})"
                                                    icon="pencil" class="cursor-pointer" >
                                        </flux:badge>
                                    </flux:tooltip>
                                </flux:modal.trigger>
                            </flux:table.cell>

                        </flux:table.row>
                    @endforeach

                </flux:table.rows>
            </flux:table>

            {{-- $this->tipos->links() --}}


        </div>

        <div class="w-1/2 ">
            <div class="relative mb-4 w-full">
                <flux:heading size="xl" level="1">{{ __('Valor') }}</flux:heading>
                <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Valor') }}

                    <!-- modal-->
                    <flux:modal.trigger name="general-modal">
                        <flux:badge
                            wire:click="$dispatch('crear-general-modal', { modelo_name: 'Clase'})"
                            icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Valor
                        </flux:badge>
                    </flux:modal.trigger>
                </flux:heading>
            </div>
            <div class="flex justify-between items-center flex-wrap gap-2">

                <!-- Search Imput -->
                <!--<flux:input wire:model.live.debounce.300ms="search" class="w-full sm:w-64" />-->

                {{--<div class="flex gap-2">
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
                </div>--}}
            </div>

            <flux:table class="max-w-9/10" >
                <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
                    <flux:table.column sorteable :sorted="$sortBy === 'nombre'" :direction="$sortDirection" wire:click="sort('nombre')">Nombre</flux:table.column>
                    <flux:table.column>favorito</flux:table.column>
                    <flux:table.column align="center">Acción</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->valores as $valor)
                        <flux:table.row :key="$valor->id">
                            <flux:table.cell>
                                {{ $valor->nombre }}
                            </flux:table.cell>

                            <flux:table.cell>

                                <flux:badge rounded wire:click="selecValor({{$valor->id}})" class="cursor-pointer mx-2" >
                                    @if($valor->seleccion)
                                        {{--<flux:switch  checked />--}}
                                        <flux:icon.check-circle variant="solid" class="text-green-600"/>
                                    @else
                                        {{--<flux:switch />--}}
                                        <flux:icon.x-circle class="text-red-700"  />
                                    @endif
                                </flux:badge>

                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:modal.trigger name="general-edit-modal">
                                    <flux:tooltip content="Editar Tipo Doc.">
                                        <flux:badge color="indigo" as="button"
                                                    wire:click="$dispatch('editar-general-modal', { modelo_name: 'TipoDocumento', tipo: {{$valor}}})"
                                                    icon="pencil" class="cursor-pointer" >
                                        </flux:badge>
                                    </flux:tooltip>
                                </flux:modal.trigger>
                            </flux:table.cell>

                        </flux:table.row>
                    @endforeach

                </flux:table.rows>
            </flux:table>

            {{-- $this->tipos->links() --}}


        </div>
    </div>

</div>
