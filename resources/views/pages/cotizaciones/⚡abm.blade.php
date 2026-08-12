<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Number;


new class extends Component
{
    public $modo;
    public $fecha;
    public $monedaid;
    public $cotizacions;

    public $cotizacion;

    #[On('cotizacion-crear')]
    public function crearCotizacion($modo): void
    {
        $this->modo = $modo;

    }

    #[On('cotizacion-editar')]
    public function editarCotizacion($modo, \App\Models\Cotizacion $cotizacion): void
    {
        $this->modo = $modo;
        $this->cotizacion = $cotizacion;

        $this->fecha = $this->cotizacion->fecha;
        $this->monedaid = $this->cotizacion->moneda_id;
    //    $this->cotizacions = $this->cotizacion->cotizacion;
        $this->cotizacions =  Number::format($this->cotizacion->cotizacion, locale: 'es');

    }

    #[Computed]
    public function monedas()
    {
        return \App\Models\Moneda::select('id', 'nombre')->get();
    }

    public function grabarCotizacion()
    {
        $this->cotizacions = str_replace('.', '', $this->cotizacions);
        $validated = $this->validate([
                                         'fecha' => ['required', 'string', 'max:150'],
                                         'monedaid' => ['required', 'exists:monedas,id'],
                                         'cotizacions' => ['required', 'regex:/^\d+(,\d+)?$/'],
                                     ],
                                     [
                                         'fecha' => 'Se queriere ingresar una Fecha',
                                         'monedaid' => 'Se queriere ingresar una Moneda',
                                         'cotizacions' => 'Se queriere ingresar una cotizacion',
                                     ]);

        $this->cotizacions = str_replace(',', '.', $this->cotizacions);

        if ($this->modo == 'Alta')
        {

            //dump($this->modo);
            DB::transaction(function()  {
                $cotiza = \App\Models\Cotizacion::create([
                                                             'fecha' => $this->fecha,
                                                             'moneda_id' => $this->monedaid,
                                                             'cotizacion' => $this->cotizacions,
                                                         ]);
                //dump($cotiza);

            });
        } else {
            //dump($this->modo);

            $this->cotizacion->fecha = $this->fecha;
            $this->cotizacion->moneda_id = $this->monedaid;
            $this->cotizacion->cotizacion = $this->cotizacions;
            $this->cotizacion->save();
        }

        Flux::modal('cotizacion-modal')->close();
        $this->dispatch('refreshComponent')->to('pages::cotizaciones.index');
    }

};
?>

<div>
    <flux:modal name="cotizacion-modal" class="min-w-[40rem]">
        <div>
            <flux:heading class="font-bold bg-red-100 text-center" size="lg">{{$this->modo}} - Cotización</flux:heading>
        </div>

        <flux:card>
            {{-- 1° fila --}}
            <div class="flex w-full flex-row items-start space-x-4 text-left">
                {{-- fecha --}}
                <div class="w-1/4">
                    <flux:input type="date" wire:model="fecha" label="Fecha" placeholder="Ingrese una fecha" />
                </div>
                {{-- Moneda --}}
                <div class="w-1/2">
                    <flux:select wire:model="monedaid" label="Moneda" placeholder="Seleccione una Moneda">
                        <flux:select.option>-</flux:select.option>
                        @foreach ($this->monedas as $moneda)
                            <flux:select.option value="{{ $moneda->id }}" wire:key="{{ $moneda->id }}">{{ $moneda->nombre }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Cotización --}}
                <div class="w-1/4">
                    <flux:input type="text" wire:model="cotizacions" label="Cotización $" placeholder="cotización" />
                </div>
            </div>
        </flux:card>

        {{-- buttones--}}
        <div class="flex justify-end pt-4">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>

            </flux:modal.close>

            <flux:button wire:click="grabarCotizacion()" variant="primary" wireclass="cursor-pointer ms-2">Grabar</flux:button>

        </div>

    </flux:modal>
</div>
