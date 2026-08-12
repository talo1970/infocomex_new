<?php

use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{

    #[Computed]
    public function productos()
    {
        return \App\Models\Producto::select('id', 'nombre', 'honorario', 'abreviado', 'color_web')->orderby('id')->get();
    }

    // cambio en slección código
    public function selecHonorario($producto)
    {
        $unSelec = \App\Models\Producto::find($producto['id']);
        $unSelec->honorario = !$unSelec->honorario;
        $unSelec->save();
    }
};
?>

<div class="space-y-4">
    <div class="relative mb-4 w-full">
        <flux:heading size="xl" level="1">{{ __('Producto') }}</flux:heading>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Administración de Productos') }}
        </flux:heading>
    </div>



    <flux:table class="max-w-9/10" >
        <flux:table.columns class="bg-indigo-100 dark:bg-blue-400 text-blue-600" >
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Abreviado</flux:table.column>
            <flux:table.column>Honorario</flux:table.column>
            <flux:table.column>color</flux:table.column>
            {{--<flux:table.column align="center">Acción</flux:table.column>--}}
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->productos as $producto)

                <flux:table.row :key="$producto->id">
                    <flux:table.cell>
                        <flux:badge color="{{ $producto->color_web }}" as="button">
                            {{ $producto->nombre }}
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $producto->abreviado }}
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:badge rounded wire:click="selecHonorario({{$producto}})" class="cursor-pointer mx-2" >
                            @if($producto->honorario)
                                <flux:icon.check-circle variant="solid" class="text-green-600"/>
                            @else
                                {{--<flux:switch />--}}
                                <flux:icon.x-circle class="text-red-700"  />
                            @endif
                        </flux:badge>
                    </flux:table.cell>


                    <flux:table.cell>
                        <flux:badge color="{{ $producto->color_web }}" as="button">
                        {{ $producto->color_web }}
                        </flux:badge>

                    </flux:table.cell>
                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>

    {{-- $this->productos->links() --}}

</div>
