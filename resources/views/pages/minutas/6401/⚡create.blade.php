<?php

    use Livewire\Attributes\Computed;
    use Livewire\Component;

    new class extends Component {

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::select('id', 'nombre')->get();
        }

        #[Computed]
        public function clientes()
        {
            return \App\Models\Entidad::clientes()->select('id', 'razon_social')->get();
        }

        #[Computed]
        public function bancos()
        {
            return \App\Models\Entidad::bancos()->select('id', 'razon_social')->get();
        }

    };
?>

<div>
    <flux:modal name="minuta-cambio-crear-modal" class="min-w-[64rem]">
        <form wire:submit="grabarMinuta6401" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-800" size="lg">Datos de la Minuta 6401
                </flux:heading>
            </div>
            <flux:card>
                {{-- nro estado y fecha --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Número --}}
                    <div class="w-1/4">
                        <flux:input wire:model="numeroboleto" label="Número" />
                    </div>
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
                    {{-- Vendedor --}}
                    <div class="w-1/2">
                        <flux:select variant="listbox" searchable wire:model.live="bancoid" label="Banco"
                                     placeholder="Seleccione un Banco">
                            @foreach ($this->bancos as $banco)
                                <flux:select.option value="{{ $banco->id }}"
                                                    wire:key="{{ $banco->id }}">{{ $banco->razon_social }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                <flux:separator class="my-4" />
                {{-- 2° fila --}}
                <div class="ml-32 flex w-3/4 flex-row justify-between space-x-4 text-left">
                    <div class="w-1/2 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos desde mes--}}
                        <div class="w-28">
                            <flux:label>Periodo desde</flux:label>
                        </div>
                        <div class="w-16">
                            <flux:input wire:model="periododmes" />
                        </div>
                        {{-- Períodos desde año--}}
                        <div class="w-32">
                            <flux:input wire:model="periododanio" />
                        </div>
                    </div>
                    <div class="w-1/2 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos hasta mes--}}
                        <div class="w-28">
                            <flux:label>Periodo hasta</flux:label>
                        </div>
                        <div class="w-16">
                            <flux:input wire:model="periodohmes" />
                        </div>
                        {{-- Períodos hasta año--}}
                        <div class="w-32">
                            <flux:input wire:model="periodohanio"/>
                        </div>
                    </div>
                </div>
                {{-- 3° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- observación --}}
                    <div class="w-full">
                        <flux:textarea rows="2" wire:model.enter.live="observacion" label="Observación" />
                    </div>
                </div>
                <flux:card class="mt-2">
                    {{-- 4ª fila --}}
                    <div class="flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Vendedor --}}
                        <div class="w-1/3">
                            <flux:input wire:model="nombre_vendedor" label="Vendedor" />
                        </div>
                        {{-- comisión dolares --}}
                        <div class="w-1/3">
                            <flux:input wire:model="comisiondolares" label="Comisión U$S" />
                        </div>
                        {{-- Total comisión en dolares --}}
                        <div class="w-1/5">
                            <flux:input wire:model="totalcomisiondolares" label="Total Comisión U$S" />
                        </div>
                    </div>

                    <hr class="ml-78 my-4" style="border: none; height: 2px; background-color: #333; width: 65%;">
                    {{-- 5ª fila --}}
                    <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Tipo de cambio --}}
                        <div class="ml-78 w-1/3">
                            <flux:input wire:model.model.live="tipocambio" wire:keydown.enter="entertipocambio"
                                        label="Tipo de Cambio" />
                        </div>
                        {{-- equivalente --}}
                        <div class="w-1/3">
                            <flux:input icon="currency-dollar" wire:model="totalminuta" readonly
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
                <flux:button wire:click="grabarMinutaBoleto()" variant="primary"
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

    </flux:modal> {{-- The whole world belongs to you. --}}

</div>
