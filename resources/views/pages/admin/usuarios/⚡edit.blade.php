<?php

    use Livewire\Attributes\On;
    use Livewire\Component;
    use App\Models\User;

    new class extends Component {
        public User $user;
        public $name;
        public $cuil;
        public $email;
        public $rolid;

        #[On('editarUsuario')]
        public function editarUsuario(User $user = null): void
        {
            $this->user = $user;

            $this->name  = $this->user->name;
            $this->cuil  = $this->user->cuil;
            $this->email = $this->user->email;
            $this->rolid = $this->user->rol_id;
        }

        public function cancel(): void
        {
            $this->modal('show-usuario-modal')->close();
        }

        public function grabarUsuario()
        {
            $validated = $this->validate([
                                             'cuil'  => ['required','numeric',],
                                             'name'  => ['required','string','max:50',],
                                             'email' => 'nullable|email:rfc,dns',
                                             'rolid' => [ 'required' ],
                                         ],
                                         [
                                             'cuil'    => 'debe ser un cuil valido',
                                             'nombre'  => 'Es necesario ingresar un nombre',
                                             'email.*' => 'Debe ser un email válido y real',
                                             'rolid'   => 'Es necesario asignarle un rol',
                                         ]
            );

            DB::transaction(function()  {
                $this->user->name   = $this->name;
                $this->user->cuil   = $this->cuil;
                $this->user->email  = $this->email;
                $this->user->rol_id = $this->rolid;

                if ($this->user->isDirty()) {
                    $this->user->save();
                }
             });
            $this->dispatch('refreshComponent')->to('pages::admin.usuarios.index');
            $this->reset();

            Flux::toast(heading: 'cambios', text:'Grabado con Exito...', variant:'success');

            $this->modal('edit-usuario-modal')->close();


        }
    };
?>

<div>
    <flux:modal name="edit-usuario-modal" class="min-w-[48rem]">
        <form wire:submit="grabarUsuario" class="space-y-6">
            <div>
                <flux:heading class="font-bold" size="lg">Editar Usuario</flux:heading>
            </div>

            <flux:card>
                <!-- 1ª fila-->
                <div class="my-4 grid grid-cols-2 gap-x-4">
                    {{-- cuil --}}
                    <flux:input wire:model="cuil" label="Cuil"/>
                    {{-- Nombre --}}
                    <flux:input wire:model="name" label="Nombre"/>
                </div>
                <div class="grid grid-cols-2 gap-x-4">
                    {{-- Correo --}}
                    <flux:input wire:model="email" label="Correo Electrónico"/>


                </div>
            </flux:card>
            {{-- buttones--}}
            <div class="flex justify-end pt-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button wire:click="cancel()" variant="ghost" wireclass="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="cursor-pointer ms-2">
                    Grabar Usuario
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
