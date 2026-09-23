<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>

    <flux:modal name="minuta-crear-modal" class="min-w-[64rem]">
        <form wire:submit="grabarSuscripcion" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-800"
                              size="lg">Datos de la Suscripción - {{$this->productoNombre}}
                </flux:heading>
            </div>
            <flux:card>
                {{-- nro estado y fecha --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Número --}}
                    <div class="w-1/4">
                        <flux:input wire:model="numeroboleto" label="Número" />
                    </div>
                    {{-- Estado --}}
                    <div class="w-1/2">
                        <flux:select searchable wire:model="estadoid" label="Estado" placeholder="Seleccione un Estado">
                            @foreach ($this->estados as $estado)
                                @if ($estado->id == 1)
                                    {{$this->estadoid = 1}}
                                @endif
                                <flux:select.option value="{{ $estado->id }}"
                                                    wire:key="{{ $estado->id }}">{{ $estado->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Fecha --}}
                    <div class="w-1/2">
                        <flux:date-picker type="input" label="Fecha" wire:model="fechaboleto" />
                    </div>
                </div>
                {{-- 1° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Cliente --}}
                    <div class="w-1/2">
                        <flux:select variant="listbox" searchable wire:model.live="entidadid" label="Cliente"
                                     placeholder="Seleccione un Cliente">
                            @foreach ($this->clientes as $cliente)
                                <flux:select.option value="{{ $cliente->id }}"
                                                    wire:key="{{ $cliente->id }}">{{ $cliente->razon_social }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Tipo documento --}}
                    <div class="w-1/2">
                        <flux:select variant="listbox" searchable wire:model.live="tipodocid" label="Cliente"
                                     placeholder="Seleccione un Cliente">
                            @foreach ($this->tipoDocumentos as $tipo)
                                <flux:select.option value="{{ $tipo->id }}"
                                                    wire:key="{{ $tipo->id }}">{{ $tipo->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                <flux:separator class="my-4" />
                {{-- 2° fila --}}
                <div class="ml-32 flex flex-row justify-between space-x-4 text-left">
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- año desde--}}
                        <div class="w-28 text-right">
                            <flux:label>Plazo:</flux:label>
                        </div>
                        {{-- Año desde--}}
                        <div class="w-24">
                            <flux:input wire:model.live="plazo" maxlength="4" />
                        </div>
                    </div>
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- Año hasta--}}
                        <div class="w-28 text-right">
                            <flux:label>Fecha Vencimiento:</flux:label>
                        </div>
                        <div class="w-24">
                            <flux:date-picker wire:model="fecha_vto"/>
                        </div>
                    </div>
                </div>
                {{-- 3° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- referencia --}}
                    <div class="w-full">
                        <flux:select variant="listbox" searchable wire:model.live="referenciaid" label="Referencia" placeholder="Seleccione una Referencia">
                            @foreach ($this->referencias as $referencia)
                                <flux:select.option value="{{ $referencia->id }}"
                                                    wire:key="{{ $referencia->id }}">{{ $referencia->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                {{-- 4° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- observación --}}
                    <div class="w-full">
                        <flux:textarea rows="2" wire:model.enter.live="observacion" label="Observación" />
                    </div>
                </div>
                {{-- 4ª fila --}}
                <flux:card class="mt-2">
                    {{-- 4ª fila --}}
                    <div class="flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Vendedor --}}
                        <div class="w-1/3">
                            <flux:select :disabled="true" wire:model="vendedorid" label="Vendedor">
                                <flux:select.option>-</flux:select.option>
                                @foreach($this->vendedores as $vendedor)
                                    <flux:select.option value="{{$vendedor->id}}"
                                                        wire:key="{{$vendedor->id}}">{{$vendedor->name}}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        {{-- comisión dolares --}}
                        <div class="w-1/3">
                            <flux:input wire:model.live="comisiondolares" label="Comisión U$S" />
                        </div>
                        {{-- Total comisión en dolares --}}
                        <div class="w-1/5">
                            <flux:input :disabled="true" wire:model="totalcomisiondolares" label="Total Comisión U$S" />
                        </div>
                    </div>

                    <hr class="ml-78 my-4" style="border: none; height: 2px; background-color: #333; width: 65%;">
                    {{-- 5ª fila --}}
                    <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Tipo de cambio --}}
                        <div class="ml-78 w-1/3">
                            <flux:input wire:model.model.live="tipocambio"
                                        label="Tipo de Cambio" />
                        </div>
                        {{-- total minuta --}}
                        <div class="w-1/3">
                            <flux:input :disabled="true" icon="currency-dollar" wire:model="totalminuta" readonly
                                        label="Total Comisión $" />
                        </div>
                    </div>
                </flux:card>
            </flux:card>

            {{-- buttones--}}
            <div class="flex justify-end">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button wire:click="cancel()" variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary"
                             wireclass="cursor-pointer ms-2">Grabar Minuta
                </flux:button>
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

    </flux:modal>

</div>
