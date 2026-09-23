<?php

    use Flux\Flux;
    use Livewire\Attributes\On;
    use Livewire\Component;
    use Illuminate\Support\Number;


    new class extends Component {
        public $producto;
        public $honorario;
        public $id;
        public $importe;
        public $nombreProducto;

        #[On('editar-honorario-modal')]
        public function editarhonorarios(\App\Models\Producto $producto, \App\Models\HonorarioProducto $honorario): void
        {
            $this->producto       = $producto;
            $this->nombreProducto = $this->producto->nombre;

            $this->honorario = $honorario;
            $this->id        = $this->honorario->honorario_id;
            //$this->importe = $this->honorario->importe;
            $this->importe = Number::format($this->honorario->importe, locale: 'es');

        }

        public function grabarHonorario()
        {
            $this->importe = str_replace('.', '', $this->importe);

            $validated     = $this->validate([
                                                 'importe' => [
                                                     'required',
                                                     'regex:/^\d+(,\d+)?$/'
                                                 ],
                                             ], [
                                                 'importe' => 'Se queriere ingresar un Importe',
                                             ]);
            $this->importe = str_replace(',', '.', $this->importe);

            $this->honorario->importe = $this->importe;
            $this->honorario->save();

            Flux::modal('honorario-edit-modal')->close();
            $this->dispatch('refreshComponent')->to('pages::honorarios.index');


        }

    };
?>

<div>
    <flux:modal name="honorario-edit-modal" class="min-w-[32rem]">
        <form wire:submit="grabarHonorarios" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-700"
                              size="lg">Editar Honorarios de producto - {{$this->nombreProducto}}</flux:heading>
            </div>

            <flux:card>
                {{-- 1° fila --}}
                <div class="flex w-full flex-row space-x-4 justify-center">
                    {{-- 1 --}}
                    <div class="w-1/2">
                        <flux:input wire:model="importe" label="honorario {{$this->id}}" />
                    </div>
                </div>
            </flux:card>

            {{-- buttones--}}
            <div class="flex justify-end pt-4">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>

                </flux:modal.close>

                <flux:button wire:click="grabarHonorario()" variant="primary" wireclass="cursor-pointer ms-2">Grabar
                </flux:button>

            </div>
        </form>

    </flux:modal>

</div>
