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


    protected $listeners = [
        'subirDocumento',
        'uploadedArchivo',
    ];

    public function mount( $minuta , $fecha)
{
        $this->vencimiento = $fecha;

        if ($minuta != null){
           $subcritores_minuta = \App\Models\Minuta::find($minuta);
           $this->id =  $subcritores_minuta->id;

           foreach ($subcritores_minuta as $suscritor)
                $this->suscritores = [
                    'id' => $suscritor->id,
                    'nombre' => $suscritor->nombre,
                    'inicio' => $suscritor->inicio,
                    'fin' => $suscritor->fin,
                    'dias' => $suscritor->dias,
                    'importe_comision' => $suscritor->importe_comision,
                    'completo' => $suscritor->completo,
                ];
       } else {
           $this->suscritores = [];
           $this->id = 1;
       }
        // $this->minuta = $minuta;
       // $this->model = \App\Models\Minuta::class;
        //$this->id = $minuta->id;
    }

    public function uploadedArchivo($archivo)
    {
        $this->archivos_tmp[] = $archivo;
        $this->CantArchivos   = count($this->archivos_tmp);
    }



};
?>

<div>
    <livewire:pages::minutas.suscripciones.suscriptores.crear />

    <div class="my-2 mx-2 flex justify-end items-center flex-wrap gap-2">

        <!-- modal-->
        <flux:modal.trigger name="crear-suscriptor-modal">
            <flux:button
                wire:click="$dispatch('crear-suscriptor', { minuta: '{{$this->id}}', vencimiento:'{{$this->vencimiento}}'})"
                size="sm" icon="plus-circle" class="cursor-pointer" variant="primary" color="red">Suscriptor</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:table class="max-w-9/10" >
        <flux:table.columns class="h-4 bg-indigo-100 dark:bg-blue-400 text-blue-600">
            <flux:table.column>Suscritor</flux:table.column>
            <flux:table.column>Inicial</flux:table.column>
            <flux:table.column>Vencimiento</flux:table.column>
            <flux:table.column>Dias</flux:table.column>
            <flux:table.column align="center">Acción</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($this->suscritores as $suscripto)

                <flux:table.row :key="$suscripto->id">
                    <flux:table.cell>
                        {{ $suscripto->contacto }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $suscripto->mail }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $suscripto->telefono }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ $suscripto->provincia->nombre }}
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:modal.trigger name="contacto-show-modal">
                            <flux:tooltip content="Consulta Contacto">
                                <flux:badge color="sky" as="button"
                                            wire:click="$dispatch('show-contacto-modal', { modo: 'show', contacto: {{$suscripto}}})"
                                            icon="eye" class="cursor-pointer" >
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>

                        <flux:modal.trigger name="contacto-edit-modal">
                            <flux:tooltip content="Editar Contacto">
                                <flux:badge color="indigo" as="button"
                                            wire:click="$dispatch('edit-contacto-modal', { modo: 'edit', contacto: {{$suscripto}}})"
                                            icon="pencil" class="cursor-pointer" >
                                </flux:badge>
                            </flux:tooltip>
                        </flux:modal.trigger>


                    </flux:table.cell>

                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>
</div>
