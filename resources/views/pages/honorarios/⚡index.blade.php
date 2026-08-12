<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Number;


new class extends Component
{
    public $productoid;
    public $productoHonorarioss;

    protected $listeners = ['refreshComponent' => '$refresh'];


    #[Computed]
    public function productoHonorarios()
    {
        return \App\Models\HonorarioProducto::orderBy('producto_id')->get();
                //dump($query->toSql());
    }

    public function honorariosProducto($id)
    {
        $this->productoHonorarioss =\App\Models\HonorarioProducto::where('producto_id', '=', $id)->orderBy('honorario_id')->get();
    }

    #[Computed]
    public function productos()
    {
        return \App\Models\Producto::where('honorario', '=', true)->get();
    }

};
?>

<div class="space-y-4">
    <livewire:pages::honorarios.edit/>

    <div class="relative mb-4 w-full">
        <flux:heading size="xl" level="1">{{ __('Honorarios') }}</flux:heading>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Honorarios de Producto') }}

        </flux:heading>
    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
            <flux:table.column align="center">&emsp;Producto</flux:table.column>
            <flux:table.column align="center">&emsp;Honorario 1</flux:table.column>
            <flux:table.column align="center"> Honorario 2</flux:table.column>
            <flux:table.column align="center"> Honorario 3</flux:table.column>
            <flux:table.column align="center"> Honorario 4</flux:table.column>
            <flux:table.column align="center"> Honorario 5</flux:table.column>
            <flux:table.column align="center"> Honorario 6</flux:table.column>
            <flux:table.column align="center"> Honorario 7</flux:table.column>
            <flux:table.column align="center"> Honorario 8</flux:table.column>
            <flux:table.column align="center"> Honorario 9</flux:table.column>
            <flux:table.column align="center"> Honorario 10</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach($this->productos as $producto)
                <flux:table.row :key="$producto->id">
                    <!-- producto -->
                    <flux:table.cell>
                        {{ $producto->nombre }}
                    </flux:table.cell>

                    {{ $this->honorariosProducto($producto->id) }}
                    @foreach($this->productoHonorarioss as $hora)
                        <flux:table.cell>
                            <flux:modal.trigger name="honorario-edit-modal">
                                <flux:input class="justify-center" wire:click="$dispatch('editar-honorario-modal', { producto: {{$producto}}, honorario: {{$hora}}})"
                                            readonly value="{{ Number::format($hora->importe, locale: 'es')}}"/>
                            </flux:modal.trigger>
                        </flux:table.cell>
                    @endforeach
                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>
</div>
