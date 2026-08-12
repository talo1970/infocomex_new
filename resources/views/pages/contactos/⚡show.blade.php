<?php

use Livewire\Component;
use Livewire\Attributes\On;


new class extends Component
{
    public $contacto_id;

    public $contacto;
    public $nombre;
    public $mail;
    public $telefono;
    public $domicilio;
    public $numero;
    public $codigoPostal;
    public $deptopiso;
    public $localidad;
    public $provincianombre;
    public $pais;

    /*
    public function mount($entidad): void
    {
        //dump($entidad);
        $entidad_id = $entidad;
        dump($entidad_id);
    }
    */

    #[On('show-contacto-modal')]
    public function contactoCrear($modo, \App\Models\Contacto $contacto): void
    {
        //$this->contacto_id = $contacto['id'];
        $this->contacto = $contacto;
        $this->contacto_id = $this->contacto->id;
        $this->nombre = $this->contacto->contacto;
        $this->mail = $this->contacto->mail;
        $this->telefono = $this->contacto->telefono;
        $this->domicilio = $this->contacto->domicilio;
        $this->numero = $this->contacto->numero;
        $this->codigoPostal = $this->contacto->codigo_postal;
        $this->deptopiso = $this->contacto->departamento_piso;
        $this->localidad = $this->contacto->localidad;
        $this->provincianombre = $this->contacto->provincia->nombre;
        $this->pais = $this->contacto->pais;
    }


};
?>

<div>
    <flux:modal name="contacto-show-modal" class="min-w-[64rem]">
        <form wire:submit="grabarContacto" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center" size="lg">Datos del Contacto</flux:heading>
            </div>

            <flux:card>
                {{-- 1° fila --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Contacto --}}
                    <div class="w-1/2">
                        <flux:input wire:model="nombre" disabled label="Contacto" placeholder="Ingrese un contacto" />
                    </div>
                    {{-- e-Mail --}}
                    <div class="w-1/2">
                        <flux:input wire:model="mail" disabled label="e-Mail" placeholder="Ingrese el e-Mail" />
                    </div>
                </div>
                {{-- 2° fila --}}
                <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Telefono --}}
                    <div class="w-1/2">
                        <flux:input wire:model="telefono" disabled label="Teléfono" placeholder="Ingrese Teléfono" />
                    </div>
                </div>
                {{-- 3° fila --}}
                <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Dirección --}}
                    <div class="w-1/2">
                        <flux:input wire:model="domicilio" disabled label="Dirección" placeholder="Ingrese la Dirección" />
                    </div>
                    {{-- Nro --}}
                    <div class="w-1/5">
                        <flux:input wire:model="numero" disabled label="Número" placeholder="Ingrese el número" />
                    </div>
                    {{-- CP --}}
                    <div class="w-1/5">
                        <flux:input wire:model="codigoPostal" disabled label="Código Postal" placeholder="Ingrese el CP" />
                    </div>
                </div>
                {{-- 5° fila --}}
                <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- piso --}}
                    <div class="w-1/5">
                        <flux:input wire:model="deptopiso" disabled label="Depto y/o piso" />
                    </div>
                    {{-- Localidad --}}
                    <div class="w-1/2">
                        <flux:input wire:model="localidad" disabled label="Localidad" />
                    </div>
                    {{-- Provincia --}}
                    <div class="w-1/4">
                        <flux:input wire:model="provincianombre" disabled label="Provincia" />
                    </div>
                    {{-- Pais--}}
                    <div class="w-1/5">
                        <flux:input wire:model="pais" disabled label="Pais" />
                    </div>
                </div>
            </flux:card>

            {{-- buttones--}}
            <div class="flex justify-end pt-4">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Aceptar</flux:button>

                </flux:modal.close>


            </div>
        </form>
        {{-- validaciones--}}
        @if($errors->any())
            <div class="aler aler-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </flux:modal> {{-- The whole world belongs to you. --}}


</div>
