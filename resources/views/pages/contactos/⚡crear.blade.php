<?php

    use Flux\Flux;
    use Livewire\Component;
    use Livewire\Attributes\Computed;
    use Livewire\Attributes\On;


    new class extends Component {
        public $entidad_id;
        public $contacto;
        public $mail;
        public $telefono;
        public $domicilio;
        public $numero;
        public $codigoPostal;
        public $deptopiso;
        public $localidad;
        public $provinciaid = 25;
        public $pais;

        /*
        public function mount($entidad): void
        {
            //dump($entidad);
            $entidad_id = $entidad;
            dump($entidad_id);
        }
        */

        #[On('crear-contacto-modal')]
        public function contactoCrear($modo, $entidad): void
        {
            $this->entidad_id = $entidad;
            //dump($entidad_id);
        }

        #[Computed]
        public function provincias()
        {
            return \App\Models\Provincia::select('id', 'nombre')->get();
        }

        public function grabarContacto(): void
        {


            $validated = $this->validate([
                                             'entidad_id'   => [ 'required' ],
                                             'contacto'     => [
                                                 'required',
                                                 'string',
                                                 'max:150'
                                             ],
                                             'mail'         => [
                                                 'nullable',
                                                 'string',
                                                 'max:150'
                                             ],
                                             'telefono'     => [
                                                 'nullable',
                                                 'string',
                                                 'max:150'
                                             ],
                                             'domicilio'    => [
                                                 'nullable',
                                                 'string',
                                                 'max:150'
                                             ],
                                             'numero'       => [
                                                 'nullable',
                                                 'string',
                                                 'max:55'
                                             ],
                                             'codigoPostal' => [
                                                 'nullable',
                                                 'string',
                                                 'max:50'
                                             ],
                                             'deptopiso'    => [
                                                 'nullable',
                                                 'string',
                                                 'max:100'
                                             ],
                                             'localidad'    => [
                                                 'nullable',
                                                 'string',
                                                 'max:150'
                                             ],
                                             'provinciaid'  => [
                                                 'required',
                                                 'exists:provincias,id'
                                             ],
                                             'pais'         => [
                                                 'nullable',
                                                 'string',
                                                 'max:150'
                                             ],
                                         ]);

            DB::transaction(function() {
                $contacto = \App\Models\Contacto::create([
                                                             'entidad_id'        => $this->entidad_id,
                                                             'contacto'          => $this->contacto,
                                                             'mail'              => $this->mail,
                                                             'telefono'          => $this->telefono,
                                                             'domicilio'         => $this->domicilio,
                                                             'numero'            => $this->numero,
                                                             'departamento_piso' => $this->deptopiso,
                                                             'codigo_postal'     => $this->codigoPostal,
                                                             'localidad'         => $this->localidad,
                                                             'provincia_id'      => $this->provinciaid,
                                                             'pais'              => $this->pais,
                                                         ]);
            });

            Flux::modal('contacto-modal')->close();
            $this->dispatch('refreshComponent')->to('pages::contactos.index');

        }

        public function cancel(): void
        {
            $this->reset();
            Flux::modal('contacto-modal')->close();

            $this->redirectRoute('entidades.edit', navigate: true);
        }

    };
?>

<div>
    <flux:modal name="contacto-modal" class="min-w-[64rem]">
        <form wire:submit="grabarContacto" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center" size="lg">Datos del Contacto</flux:heading>
            </div>

            <flux:card>
                {{-- 1° fila --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Contacto --}}
                    <div class="w-1/2">
                        <flux:input wire:model="contacto" label="Contacto" placeholder="Ingrese un contacto" />
                    </div>
                    {{-- e-Mail --}}
                    <div class="w-1/2">
                        <flux:input wire:model="mail" label="e-Mail" placeholder="Ingrese el e-Mail" />
                    </div>
                </div>
                {{-- 2° fila --}}
                <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Telefono --}}
                    <div class="w-1/2">
                        <flux:input wire:model="telefono" label="Teléfono" placeholder="Ingrese Teléfono" />
                    </div>
                </div>
                {{-- 3° fila --}}
                <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Dirección --}}
                    <div class="w-1/2">
                        <flux:input wire:model="domicilio" label="Dirección" placeholder="Ingrese la Dirección" />
                    </div>
                    {{-- Nro --}}
                    <div class="w-1/5">
                        <flux:input wire:model="numero" label="Número" placeholder="Ingrese el número" />
                    </div>
                    {{-- CP --}}
                    <div class="w-1/5">
                        <flux:input wire:model="codigoPostal" label="Código Postal" placeholder="Ingrese el CP" />
                    </div>
                </div>
                {{-- 5° fila --}}
                <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- piso --}}
                    <div class="w-1/5">
                        <flux:input wire:model="deptopiso" label="Depto y/o piso" placeholder="Ingrese el Depto" />
                    </div>
                    {{-- Localidad --}}
                    <div class="w-1/2">
                        <flux:input wire:model="localidad" label="Localidad" placeholder="Ingrese la Localidad" />
                    </div>
                    {{-- Provincia --}}
                    <div class="w-1/4">
                        <flux:select wire:model="provinciaid" label="Provicia" placeholder="Seleccione una Provincia">
                            @foreach ($this->provincias as $provincia)
                                <flux:select.option value="{{ $provincia->id }}"
                                                    wire:key="{{ $provincia->id }}">{{ $provincia->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Pais--}}
                    <div class="w-1/5">
                        <flux:input wire:model="pais" label="Pais" placeholder="Ingrese el Pais" />
                    </div>
                </div>
            </flux:card>

            {{-- buttones--}}
            <div class="flex justify-end pt-4">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancelar</flux:button>

                </flux:modal.close>

                <flux:button wire:click="grabarContacto()" variant="primary"
                             wireclass="cursor-pointer ms-2">Grabar Contacto
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
