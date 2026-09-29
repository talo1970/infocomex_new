<?php

use Livewire\Component;

new class extends Component
{
    //public $suscritores= [];
    public array $suscritores = [];

    public $minuta;
    public $id;
    public $model;

    public $inicio;
    public $vencimiento;


    protected $listeners = [ 'refreshComponent' => '$refresh',
                             'refreshSuscriptor' => 'cargarTabla'];

    public function mount( $minuta , $fecha)
{
        $this->vencimiento = $fecha;

        if ($minuta != null){
           $subcritores_minuta = \App\Models\Minuta::find($minuta);
           $this->id =  $subcritores_minuta->id;

           foreach ($subcritores_minuta as $suscritor)
                $this->suscritores = [
                    'id'          => null,
                    'nombre'      => $this->nombre,
                    'inicio'      => $this->inicio,
                    'vencimiento' => $this->fin,
                    'dias'        => $this->cantidadDias,
                    'importe'     => $this->importe,
                    'periodo'     => $this->periodo,
                ];
       } else {
           $this->suscritores = [];
           $this->id = 1;
       }
        // $this->minuta = $minuta;
       // $this->model = \App\Models\Minuta::class;
        //$this->id = $minuta->id;
    }

    public function cargarTabla($datos)
    {
        $this->suscritores[] = $datos;
        $this->dispatch('refreshSuscriptor', datos: $datos)->to('pages::minutas.suscripciones.create');

        //dd($this->suscritores);
    }


};
?>

<div>
    <livewire:pages::minutas.suscripciones.suscriptores.crear />

    <div class="my-2 mx-2 flex justify-end items-center flex-wrap gap-2">
    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="h-4 bg-indigo-100 dark:bg-blue-400 text-blue-600">
            <flux:table.column>Suscritor</flux:table.column>
            <flux:table.column>Inicial</flux:table.column>
            <flux:table.column>Dias</flux:table.column>
            <flux:table.column>Importe</flux:table.column>
            <flux:table.column align="center">Acción</flux:table.column>
        </flux:table.columns>

            <flux:table.rows>
                @forelse($this->suscritores as $key => $suscripto)
                    <flux:table.row>
                        <flux:table.cell class="text-left">
                            {{ $suscripto['nombre'] }}
                        </flux:table.cell>

                        <flux:table.cell class="text-left">
                            {{ date('d/m/Y', strtotime($suscripto['inicio'])) }}
                        </flux:table.cell>

                        <flux:table.cell class="text-left">
                            {{ $suscripto['dias'] }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $suscripto['importe'] }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:modal.trigger name="contacto-show-modal">
                                <flux:tooltip content="Consulta Contacto">
                                    <flux:badge color="sky" as="button"
                                                wire:click="$dispatch('show-contacto-modal', { modo: 'show', contacto: {{$key}}})"
                                                icon="eye" class="cursor-pointer" >
                                    </flux:badge>
                                </flux:tooltip>
                            </flux:modal.trigger>

                            <flux:modal.trigger name="contacto-edit-modal">
                                <flux:tooltip content="Editar Contacto">
                                    <flux:badge color="indigo" as="button"
                                                wire:click="$dispatch('edit-contacto-modal', { modo: 'edit', contacto: {{$key}}})"
                                                icon="pencil" class="cursor-pointer" >
                                    </flux:badge>
                                </flux:tooltip>
                            </flux:modal.trigger>


                        </flux:table.cell>

                    </flux:table.row>
                    @empty
                    <flux:table.row><flux:table.cell colspan="5" class="text-center text-zinc-500">Sin suscriptor</flux:table.cell></flux:table.row>
                @endforelse
            </flux:table.rows>
    </flux:table>
</div>
